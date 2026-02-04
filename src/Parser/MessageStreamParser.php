<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Attributes\RFC;
use UniversalMime\Encoding\Transfer\TransferDecoderProvider;
use UniversalMime\Model\Body;
use UniversalMime\Model\ContentType;
use UniversalMime\Model\HeaderBag;
use UniversalMime\Model\Message;
use UniversalMime\Parser\Stream\StreamInterface;
use UniversalMime\Wire\Header\RawHeaderBlock;
use UniversalMime\Wire\Header\RawHeaderLine;

/**
 * MessageStreamParser
 *
 * Lit un message depuis un flux en streaming, selon RFC 5322.
 * Produit :
 *   - StartLine
 *   - HeaderBag
 *   - Body (stream brut pour l’instant)
 */
#[RFC([
    ['5322', 'Internet Message Format'],
    ['7230', 'HTTP Message Syntax (Start-Line)'],
    ['3261', 'SIP Message Format (Start-Line)'],
])]
final class MessageStreamParser implements MessageParserInterface
{
    public function __construct(
        private readonly StartLineParserInterface $startLineParser,
        private readonly HeaderParserInterface $headerParser,
        private readonly TransferDecoderProvider $decoderProvider,
        private readonly MimeParser $mimeParser
    ) {
    }

    public function parse(StreamInterface $stream): Message
    {
        $rawHeaders = new RawHeaderBlock();

        // 1. Lecture des headers ligne par ligne
        while (!$rawHeaders->isComplete()) {
            $line = $stream->readLine();

            if ($line === null) {
                break; // Pas assez de données, fin de flux
            }

            $rawHeaders->addLine(new RawHeaderLine($line));
        }

        $unfolded = $rawHeaders->unfoldedFields();

        // 2. Extraire la start-line (optionnelle)
        $startLine = null;

        if (count($unfolded) > 0 && $this->isStartLine($unfolded[0])) {
            $startLine = $this->startLineParser->parse($unfolded[0]);

            // On retire la start-line pour ne garder que les headers
            array_shift($unfolded);
        }

        // 3. Parser les headers restants
        $headersBag = $this->headerParser->parse($rawHeaders);
        // -------------------------------

        // MIME multipart
        $ctype = $headersBag->get("Content-Type");
        if ($ctype) {
            $contentType = ContentType::fromHeaderValue($ctype->value);

            if (strtolower($contentType->type) === 'multipart') {
                $parts = $this->mimeParser->parse($contentType, $stream);
                return new Message($startLine, $headersBag, null, $parts);
            }
        }

        // Sinon → body simple

        return new Message(
            startLine: $startLine,
            headers: $headersBag,
            body:  new Body($this->decodeBody($headersBag, $stream)),
        );
    }


    // ---------------------------
    // DECODAGE BODY
    // ---------------------------
    private function decodeBody(HeaderBag $headers, StreamInterface $body): StreamInterface
    {
        $decoded = $body;

        // Content-Encoding (gzip)
        $ce = $headers->get('Content-Encoding')?->value ?? null;
        if ($ce) {
            $dec = $this->decoderProvider->get($ce);
            if ($dec) {
                $decoded = $dec->decode($decoded);
            }
        }

        // Content-Transfer-Encoding (base64, qp…)
        $cte = $headers->get('Content-Transfer-Encoding')?->value ?? null;
        if ($cte) {
            $dec = $this->decoderProvider->get($cte);
            if ($dec) {
                $decoded = $dec->decode($decoded);
            }
        }

        return $decoded;
    }

    private function isStartLine(string $line): bool
    {
        // HTTP: GET / HTTP/1.1   or   HTTP/1.1 200 OK
        if (preg_match('/^(GET|POST|PUT|DELETE|OPTIONS|HEAD|TRACE|CONNECT)\s/', $line)) {
            return true;
        }
        if (preg_match('/^HTTP\/\d\.\d\s+\d{3}/', $line)) {
            return true;
        }

        // SIP: INVITE sip:… SIP/2.0   or   SIP/2.0 200 OK
        if (preg_match('/^[A-Z]+ .* SIP\/\d\.\d/', $line)) {
            return true;
        }
        if (preg_match('/^SIP\/\d\.\d\s+\d{3}/', $line)) {
            return true;
        }

        return false;
    }
}

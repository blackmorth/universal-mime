<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Attributes\RFC;
use UniversalMime\Model\Part;
use UniversalMime\Model\Body;
use UniversalMime\Model\HeaderBag;
use UniversalMime\Model\ContentType;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Wire\Header\RawHeaderLine;
use UniversalMime\Wire\Header\RawHeaderBlock;
use UniversalMime\Parser\Stream\StreamInterface;
use UniversalMime\Encoding\Transfer\TransferDecoderProvider;
use UniversalMime\Wire\Line;

/**
 * MIME / Multipart parser (RFC 2045/2046)
 *
 * SOLID V2:
 *  - dépend de HeaderParserInterface
 *  - dépend de TransferDecoderProvider
 *  - ne crée plus aucune dépendance interne
 */
#[RFC([
    ['2045', 'MIME Message Format'],
    ['2046', 'Media Types, Multipart']
])]
final class MimeParser
{
    public function __construct(
        private readonly HeaderParserInterface $headerParser,
        private readonly TransferDecoderProvider $decoderProvider
    ) {
    }

    /**
     * @return Part[]
     */
    public function parse(ContentType $contentType, StreamInterface $stream): array
    {
        if (strtolower($contentType->type) !== 'multipart') {
            return [
                new Part(
                    headers: new HeaderBag([]),
                    body: new Body($stream)
                )
            ];
        }

        $boundaryParam = $contentType->getParam('boundary');
        if (!$boundaryParam) {
            return [
                new Part(
                    headers: new HeaderBag([]),
                    body: new Body($stream)
                )
            ];
        }

        return $this->parseMultipart($boundaryParam->value, $stream);
    }

    /**
     * @return Part[]
     */
    private function parseMultipart(string $boundary, StreamInterface $stream): array
    {
        $delimiter = "--" . $boundary;
        $closeDelim = "--" . $boundary . "--";
        $parts = [];

        while (!$stream->eof()) {
            $line = $stream->readLine();
            if ($line === null) {
                break;
            }

            $trim = $this->normalizeBoundaryLine($line);

            if ($trim === $delimiter) {
                $parts[] = $this->readPart($stream, $boundary);
            }

            if ($trim === $closeDelim) {
                break;
            }
        }

        return $parts;
    }

    private function readPart(StreamInterface $stream, string $boundary): Part
    {
        $raw = new RawHeaderBlock();

        // Collecte des headers bruts
        while (!$raw->isComplete() && !$stream->eof()) {
            $line = $stream->readLine();
            if ($line === null) {
                break;
            }
            $raw->addLine(new RawHeaderLine($line));
        }

        // Parse des headers (SOLID: interface)
        $headers = $this->headerParser->parse($raw);

        // Lecture du body encodé
        $encodedStream = $this->readBodyUntilBoundary($stream, $boundary);

        // Décodage automatique Transport + Transfer
        $decodedStream = $this->decodeBody($headers, $encodedStream);

        return new Part(
            headers: $headers,
            body:   new Body($decodedStream)
        );
    }

    private function decodeBody(HeaderBag $headers, StreamInterface $encoded): StreamInterface
    {
        $decoded = $encoded;

        // 1) Content-Encoding (gzip)
        $ce = $headers->get('Content-Encoding')?->value ?? null;
        if ($ce) {
            $decoder = $this->decoderProvider->get($ce);
            if ($decoder) {
                $decoded = $decoder->decode($decoded);
            }
        }

        // 2) Content-Transfer-Encoding (base64, qp, 7bit, etc.)
        $cte = $headers->get('Content-Transfer-Encoding')?->value ?? null;
        if ($cte) {
            $decoder = $this->decoderProvider->get($cte);
            if ($decoder) {
                $decoded = $decoder->decode($decoded);
            }
        }

        return $decoded;
    }

    private function readBodyUntilBoundary(StreamInterface $stream, string $boundary): StreamInterface
    {
        $buffer = new MemoryStream();
        $delimiter = "--" . $boundary;
        $closeDelim = "--" . $boundary . "--";

        while (!$stream->eof()) {
            $line = $stream->readLine();
            if ($line === null) {
                break;
            }

            $trim = $this->normalizeBoundaryLine($line);

            if ($trim === $delimiter || $trim === $closeDelim) {
                // IMPORTANT : ne pas consommer le boundary
                $stream->unshift($line);
                return $buffer;
            }

            $buffer->write($line);
        }

        return $buffer;
    }

    private function normalizeBoundaryLine(string $line): string
    {
        return rtrim($line, " \t\r\n");
    }
}

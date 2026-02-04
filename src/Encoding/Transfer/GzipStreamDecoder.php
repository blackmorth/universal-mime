<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

use UniversalMime\Parser\Stream\StreamInterface;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Attributes\RFC;
use UniversalMime\Attributes\TransferEncoding;

#[RFC([
    ['1952', 'GZIP File Format Specification'],
    ['9110', 'HTTP Semantics: Content-Encoding = gzip'],
])]
#[TransferEncoding('gzip')]
final class GzipStreamDecoder implements TransferDecoderInterface
{
    public function decode(StreamInterface $encoded): StreamInterface
    {
        $buffer = '';

        while (!$encoded->eof()) {
            $chunk = $encoded->read(8192);
            if ($chunk === null) {
                break;
            }
            $buffer .= $chunk;
        }

        // Tentative officielle (RFC1952)
        $decoded = @gzdecode($buffer);

        if ($decoded === false) {
            // Fallback : parfois certains serveurs 3GPP/SIP envoient
            // directement des données zlib sans header gzip
            $decoded = @gzinflate($buffer);
        }

        if ($decoded === false || $decoded === '') {
            // Données non valides => set to ''
            $decoded = '';
        }

        return new MemoryStream($decoded);
    }
}

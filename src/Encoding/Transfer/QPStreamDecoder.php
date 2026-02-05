<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

use UniversalMime\Parser\Stream\StreamInterface;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Attributes\RFC;
use UniversalMime\Attributes\TransferEncoding;

#[RFC([
    ['2045', 'MIME Quoted-Printable Encoding (section 6.7)']
])]
#[TransferEncoding('quoted-printable')]
final class QPStreamDecoder implements TransferDecoderInterface
{
    public function decode(StreamInterface $encoded): StreamInterface
    {
        $input = '';

        while (!$encoded->eof()) {
            $chunk = $encoded->read(8192);
            if ($chunk === null) {
                break;
            }
            $input .= $chunk;
        }

        // RFC 2045 §6.7: transport-padding at end of encoded lines MUST be deleted.
        $withoutTransportPadding = preg_replace('/[ \t]+(?=\r\n|\n|\r|$)/', '', $input);

        // RFC 2045 §6.7: soft line breaks use "=" immediately before line break.
        $joined = preg_replace('/=\r\n|=\n|=\r/', '', $withoutTransportPadding);

        // Decode only valid =XX escapes, preserve invalid sequences as-is.
        $decoded = preg_replace_callback(
            '/=([A-Fa-f0-9]{2})/',
            static fn (array $m): string => chr(hexdec($m[1])),
            $joined
        );

        return new MemoryStream($decoded ?? '');
    }
}

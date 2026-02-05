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

        $segments = preg_split('/(\r\n|\n|\r)/', $input, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($segments === false) {
            return new MemoryStream('');
        }

        $output = '';
        $count = count($segments);

        for ($i = 0; $i < $count; $i += 2) {
            $line = $segments[$i];
            $lineBreak = $segments[$i + 1] ?? '';

            // RFC 2045 §6.7: transport-padding at end of encoded lines MUST be deleted.
            $line = preg_replace('/[ \t]+$/', '', $line) ?? $line;

            // Keep terminal encoded whitespace tokens (=20/=09) but drop accidental
            // literal WSP placed right before them by broken generators.
            $line = $this->stripPaddingBeforeTerminalEncodedWhitespace($line);

            $isSoftBreak = str_ends_with($line, '=');
            if ($isSoftBreak) {
                $line = substr($line, 0, -1);
            }

            $decodedLine = preg_replace_callback(
                '/=([A-Fa-f0-9]{2})/',
                static fn (array $m): string => chr(hexdec($m[1])),
                $line
            );

            $output .= $decodedLine ?? '';

            if (!$isSoftBreak && $lineBreak !== '') {
                $output .= "\n";
            }
        }

        return new MemoryStream($output);
    }

    private function stripPaddingBeforeTerminalEncodedWhitespace(string $line): string
    {
        if (!preg_match('/^(.*?)[ \t]+((?:=(?:20|09))+)$/', $line, $matches)) {
            return $line;
        }

        return $matches[1] . $matches[2];
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Wire\Header;

use UniversalMime\Attributes\RFC;

/**
 * Décode les encoded-words RFC 2047 dans les valeurs de headers.
 */
#[RFC([
    ['2047', 'MIME (Encoded-Word) Syntax and Character Sets'],
])]
final class EncodedWordDecoder
{
    public static function decode(string $value): string
    {
        $pattern = '/=\\?([^?\\s]+)\\?([bBqQ])\\?([^?]*)\\?=/';

        if (!preg_match_all($pattern, $value, $matches, PREG_OFFSET_CAPTURE)) {
            return $value;
        }

        $result = '';
        $lastPos = 0;
        $previousWasEncoded = false;
        $matchCount = count($matches[0]);

        for ($i = 0; $i < $matchCount; $i++) {
            $fullMatch = $matches[0][$i][0];
            $offset = $matches[0][$i][1];
            $between = substr($value, $lastPos, $offset - $lastPos);

            if (!$previousWasEncoded || trim($between) !== '') {
                $result .= $between;
            }

            $charset = $matches[1][$i][0];
            $encoding = $matches[2][$i][0];
            $encodedText = $matches[3][$i][0];

            $decoded = self::decodeEncodedWord($charset, $encoding, $encodedText);
            $result .= $decoded ?? $fullMatch;

            $lastPos = $offset + strlen($fullMatch);
            $previousWasEncoded = true;
        }

        $tail = substr($value, $lastPos);
        if (!$previousWasEncoded || trim($tail) !== '') {
            $result .= $tail;
        }

        return $result;
    }

    private static function decodeEncodedWord(string $charset, string $encoding, string $encodedText): ?string
    {
        $encoding = strtolower($encoding);
        $decoded = null;

        if ($encoding === 'b') {
            $decoded = base64_decode($encodedText, true);
        } elseif ($encoding === 'q') {
            $decoded = quoted_printable_decode(str_replace('_', ' ', $encodedText));
        }

        if ($decoded === false || $decoded === null) {
            return null;
        }

        return self::convertToUtf8($decoded, $charset);
    }

    private static function convertToUtf8(string $value, string $charset): string
    {
        $normalized = strtolower($charset);
        if ($normalized === 'utf-8' || $normalized === 'us-ascii') {
            return $value;
        }

        if (function_exists('iconv')) {
            $converted = @iconv($charset, 'UTF-8//IGNORE', $value);
            if ($converted !== false) {
                return $converted;
            }
        }

        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($value, 'UTF-8', $charset);
        }

        return $value;
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Model;

final class HeaderValueTokenizer
{
    /**
     * @return array{0:string,1:string[]}
     */
    public static function splitMainAndParameters(string $value): array
    {
        $parts = [];
        $buffer = '';
        $inQuotes = false;
        $escaped = false;
        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $char = $value[$i];

            if ($escaped) {
                $buffer .= $char;
                $escaped = false;
                continue;
            }

            if ($inQuotes && $char === '\\') {
                $buffer .= $char;
                $escaped = true;
                continue;
            }

            if ($char === '"') {
                $inQuotes = !$inQuotes;
                $buffer .= $char;
                continue;
            }

            if ($char === ';' && !$inQuotes) {
                $parts[] = trim($buffer);
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        if ($buffer !== '') {
            $parts[] = trim($buffer);
        }

        $main = $parts[0] ?? '';
        $paramStrings = array_slice($parts, 1);

        return [$main, $paramStrings];
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Wire;

final class Line
{
    public const CRLF = "\r\n";
    public const LF   = "\n";
    public const CR   = "\r";

    public static function detect(string $buffer): string
    {
        if (str_contains($buffer, self::CRLF)) {
            return self::CRLF;
        }
        if (str_contains($buffer, self::LF)) {
            return self::LF;
        }
        if (str_contains($buffer, self::CR)) {
            return self::CR;
        }
        return self::LF;
    }
}

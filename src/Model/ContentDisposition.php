<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['2183', 'Content-Disposition Header Field'],
    ['2231', 'MIME Parameter Value and Continuations (Extended Filename)'],
])]
final class ContentDisposition
{
    /**
     * @param Parameter[] $parameters
     */
    public function __construct(
        public readonly string $disposition, // "inline", "attachment"
        public readonly array $parameters = []
    ) {
    }

    public function type(): string
    {
        return strtolower($this->disposition);
    }

    public function getParam(string $name): ?Parameter
    {
        $n = strtolower($name);
        foreach ($this->parameters as $p) {
            if ($p->lowerName() === $n) {
                return $p;
            }
        }
        return null;
    }

    public static function fromHeaderValue(string $value): self
    {
        [$disposition, $paramStrings] = self::splitHeaderValue($value);
        $parameters = Parameter::parseParameters($paramStrings);

        return new self(trim($disposition), $parameters);
    }

    /**
     * @return array{0:string,1:string[]}
     */
    private static function splitHeaderValue(string $value): array
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

        $typePart = $parts[0] ?? '';
        $paramStrings = array_slice($parts, 1);

        return [$typePart, $paramStrings];
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['2045', 'MIME Part One: Format of Internet Message Bodies'],
    ['2046', 'MIME Part Two: Media Types'],
    ['6838', 'Media Type Specifications and Registration Procedures'],
    ['6839', 'Structured Syntax Suffixes'],
])]
final class ContentType
{
    /**
     * @param Parameter[] $parameters
     */
    public function __construct(
        public readonly string $type,          // "application"
        public readonly string $subtype,       // "json"
        public readonly ?string $suffix = null, // "xml" si "application/soap+xml"
        public readonly array $parameters = [] // param. MIME normaux ou étendus
    ) {
    }

    public function mime(): string
    {
        if ($this->suffix) {
            return "{$this->type}/{$this->subtype}+{$this->suffix}";
        }
        return "{$this->type}/{$this->subtype}";
    }

    public function hasParam(string $name): bool
    {
        $n = strtolower($name);
        foreach ($this->parameters as $p) {
            if ($p->lowerName() === $n) {
                return true;
            }
        }
        return false;
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
        [$typePart, $paramStrings] = self::splitHeaderValue($value);
        $typePart = strtolower(trim($typePart));

        $type = $typePart;
        $subtype = '';
        $suffix = null;

        if (str_contains($typePart, '/')) {
            [$type, $subtypePart] = explode('/', $typePart, 2);
            $type = trim($type);
            $subtypePart = trim($subtypePart);

            if (str_contains($subtypePart, '+')) {
                [$subtype, $suffix] = explode('+', $subtypePart, 2);
            } else {
                $subtype = $subtypePart;
            }
        }

        $parameters = Parameter::parseParameters($paramStrings);

        return new self($type, $subtype, $suffix, $parameters);
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

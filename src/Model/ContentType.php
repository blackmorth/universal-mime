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
}

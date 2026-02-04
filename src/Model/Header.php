<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['5322', 'Internet Message Format (Header Fields)'],
])]
final class Header
{
    public function __construct(
        public readonly string $name,    // canonical name, ex: "Content-Type"
        public readonly string $value,   // unfolded, raw
    ) {
    }

    public function lowerName(): string
    {
        return strtolower($this->name);
    }
}

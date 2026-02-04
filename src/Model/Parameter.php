<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['2231', 'MIME Parameter Value and Parameter Value Continuations'],
])]
final class Parameter
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
        public readonly ?string $charset = null,
        public readonly ?string $language = null,
    ) {
    }

    public function lowerName(): string
    {
        return strtolower($this->name);
    }

    public function hasExtended(): bool
    {
        return $this->charset !== null || $this->language !== null;
    }
}

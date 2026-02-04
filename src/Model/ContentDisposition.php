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
}

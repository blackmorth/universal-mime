<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Parser\Stream\StreamInterface;

final class Body
{
    public function __construct(
        public readonly StreamInterface $stream,
        public readonly ?string $cached = null, // buffer optionnel
    ) {
    }

    public function isBuffered(): bool
    {
        return $this->cached !== null;
    }
}

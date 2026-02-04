<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['2045', 'MIME Message Format'],
    ['2046', 'Media Types (Multipart Structure)'],
])]
final class Part
{
    /** @param Part[] $children */
    public function __construct(
        public readonly HeaderBag $headers,
        public readonly Body $body,
        public readonly array $children = [],
    ) {
    }
}

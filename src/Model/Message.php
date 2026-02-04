<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['5322', 'Internet Message Format'],
    ['2045', 'MIME Message'],
])]
final class Message
{
    /** @param Part[] $parts */
    public function __construct(
        public readonly ?StartLine $startLine,
        public readonly HeaderBag $headers,
        public readonly ?Body $body = null,
        public readonly array $parts = [],
    ) {
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

/**
 * Représente une valeur de header brute (unfolded).
 * Le parsing est délégué à ContentType, ContentDisposition, Parameter, etc.
 */
#[RFC([
    ['5322', 'Internet Message Format (Header Field Values)'],
])]
final class HeaderValue
{
    public function __construct(
        public readonly string $raw
    ) {
    }

    public function __toString(): string
    {
        return $this->raw;
    }
}

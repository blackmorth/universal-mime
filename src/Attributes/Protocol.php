<?php

declare(strict_types=1);

namespace UniversalMime\Attributes;

use Attribute;

/**
 * Déclare les protocoles pour lesquels une classe est applicable.
 * Exemple : #[Protocol(['HTTP', 'SIP'])]
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Protocol
{
    /**
     * @param string[] $protocols
     */
    public function __construct(
        public array $protocols,
    ) {
    }
}

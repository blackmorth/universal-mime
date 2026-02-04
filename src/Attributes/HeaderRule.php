<?php

declare(strict_types=1);

namespace UniversalMime\Attributes;

use Attribute;

/**
 * Définit une règle pour un header selon une RFC.
 * Exemples :
 *   #[HeaderRule('Content-Type', required: true)]
 *   #[HeaderRule('Date')]
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
final class HeaderRule
{
    public function __construct(
        public string $name,
        public bool $required = false,
    ) {
    }
}

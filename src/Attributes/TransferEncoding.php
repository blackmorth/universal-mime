<?php

declare(strict_types=1);

namespace UniversalMime\Attributes;

use Attribute;

/**
 * Déclare que cette classe implémente un Transfer-Encoding MIME (RFC 2045).
 *
 * Exemple:
 * #[TransferEncoding('base64')]
 * #[TransferEncoding('quoted-printable')]
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class TransferEncoding
{
    public function __construct(
        public readonly string $encoding
    ) {
    }
}

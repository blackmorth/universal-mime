<?php

declare(strict_types=1);

namespace UniversalMime\Attributes;

use Attribute;

/**
 * Associe une classe BodyContext à un ou plusieurs Content-Type MIME.
 * Exemples :
 *   #[MimeContext(['application/json'])]
 *   #[MimeContext(['application/*+json'], priority: 50)]
 *   #[MimeContext(['application/sdp'], priority: 100)]
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class MimeContext
{
    /**
     * @param string[] $mimeTypes ex: ["application/json", "application/*+xml"]
     */
    public function __construct(
        public array $mimeTypes,
        public int $priority = 0,
    ) {
    }
}

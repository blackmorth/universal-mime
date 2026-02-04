<?php

declare(strict_types=1);

namespace UniversalMime\Attributes;

use Attribute;

/**
 * Contexte protocolaire : SIP, HTTP, SMTP, CPIM, MSRP…
 * Exemples :
 *   #[MessageContext(['sip'])]
 *   #[MessageContext(['http', 'websocket'])]
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class MessageContext
{
    /**
     * @param string[] $protocols ex: ["sip", "http"]
     */
    public function __construct(
        public array $protocols,
        public int $priority = 0,
    ) {
    }
}

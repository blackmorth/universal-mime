<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\Protocol;

/**
 * Représente une ligne de démarrage générique (HTTP/SIP/RTSP…)
 * Peut représenter :
 *   - Request :   "INVITE sip:user@domain SIP/2.0"
 *   - Response :  "SIP/2.0 200 OK"
 */
#[Protocol(['HTTP', 'SIP'])]
final class StartLine
{
    public function __construct(
        public readonly string $raw,
        public readonly string $type = 'unknown',
    ) {
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Attributes\Protocol;
use UniversalMime\Attributes\RFC;
use UniversalMime\Model\StartLine;
use UniversalMime\Wire\Line;

/**
 * Analyse une Start-Line générique (HTTP, SIP ou autre).
 * Ne valide pas le protocole : extraction minimale.
 */
#[RFC([
    ['7230', 'HTTP Start-Line Syntax'],
    ['3261', 'SIP Start-Line Syntax'],
])]
#[Protocol(['HTTP', 'SIP'])]
final class StartLineParser implements \UniversalMime\Parser\StartLineParserInterface
{
    /**
     * Analyse une start-line brute et retourne un StartLine immuable.
     *
     * @param string $line Ligne brute sans CRLF
     */
    public function parse(string $line): StartLine
    {
        $line = rtrim($line, Line::CRLF);

        if ($line === '') {
            return new StartLine('');
        }

        // Type léger basé sur le premier token
        $type = $this->detectType($line);

        return new StartLine(
            raw: $line,
            type: $type,
        );
    }

    /**
     * Détecte simplistement le type de start-line.
     */
    private function detectType(string $line): string
    {
        // HTTP response: "HTTP/1.1 200 OK"
        if (preg_match('/^HTTP\/\d\.\d\s+\d{3}/', $line)) {
            return 'response';
        }

        // SIP response: "SIP/2.0 200 OK"
        if (preg_match('/^SIP\/\d\.\d\s+\d{3}/', $line)) {
            return 'response';
        }

        // HTTP request: "GET /index.html HTTP/1.1"
        // SIP request: "INVITE sip:bob@localhost SIP/2.0"
        if (preg_match('/^[A-Z]+ /', $line)) {
            return 'request';
        }

        return 'unknown';
    }
}

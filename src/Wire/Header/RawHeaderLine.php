<?php

declare(strict_types=1);

namespace UniversalMime\Wire\Header;

use UniversalMime\Attributes\RFC;
use UniversalMime\Wire\Line;

/**
 * Représente une ligne brute d'un header MIME/Message selon RFC 5322.
 * Sans parsing, sans unfolding, sans trimming. Contient CRLF si présent.
 */
#[RFC([
    ['5322', 'Internet Message Format (Header Fields and Line Folding)'],
])]
final class RawHeaderLine
{
    public function __construct(
        public readonly string $raw,
    ) {
    }

    /**
     * Détecte si la ligne courante est une continuation (folded line).
     * RFC 5322 section 2.2.3: lines beginning with WSP = continuation.
     */
    public function isContinuation(): bool
    {
        if ($this->raw === '' || $this->raw === Line::CRLF) {
            return false;
        }

        $first = $this->raw[0] ?? '';
        return $first === ' ' || $first === "\t";
    }

    /**
     * Renvoie la ligne sans CRLF. Utile pour certains codecs rapides.
     */
    public function withoutCrlf(): string
    {
        return rtrim($this->raw, Line::CRLF);
    }
}

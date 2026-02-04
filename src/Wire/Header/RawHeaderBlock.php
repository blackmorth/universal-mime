<?php

declare(strict_types=1);

namespace UniversalMime\Wire\Header;

use UniversalMime\Attributes\RFC;
use UniversalMime\Wire\Line;

/**
 * Représente un bloc complet de headers RAW selon RFC 5322.
 * Pas encore décodé en objets Header, seulement regroupé et unfoldé.
 */
#[RFC([
    ['5322', 'Header Section Structure and Unfolding Rules'],
])]
final class RawHeaderBlock
{
    /** @var RawHeaderLine[] */
    private array $lines = [];

    /** @var array<int, RawHeaderLine[]> groupe de lines par header-field */
    private array $groups = [];

    private bool $complete = false;

    public function __construct(
        private readonly HeaderCodec $codec = new HeaderCodec(),
    ) {
    }

    /**
     * Ajoute une ligne brute. Détecte fin des headers (ligne vide).
     */
    public function addLine(RawHeaderLine $line): void
    {
        if ($this->complete) {
            return; // headers déjà complets, on ignore
        }

        $this->lines[] = $line;

        // Fin des headers : ligne vide ou CRLF seul
        if ($line->raw === Line::CRLF || $line->raw === "") {
            $this->complete = true;
            return;
        }

        // Création ou continuation d'un groupe
        $isContinuation = $line->isContinuation();

        if ($isContinuation && !empty($this->groups)) {
            $index = array_key_last($this->groups);
            $this->groups[$index][] = $line;
        } else {
            // Nouvelle entrée
            $this->groups[] = [$line];
        }
    }

    public function isComplete(): bool
    {
        return $this->complete;
    }

    /**
     * Retourne toutes les lignes RAW.
     * @return RawHeaderLine[]
     */
    public function rawLines(): array
    {
        return $this->lines;
    }

    /**
     * Retourne les champs unfoldés, prêts pour splitField().
     *
     * @return string[] liste des champs unfolded ("Subject: hello world")
     */
    public function unfoldedFields(): array
    {
        $out = [];

        foreach ($this->groups as $group) {
            $out[] = $this->codec->unfold($group);
        }

        return $out;
    }
}

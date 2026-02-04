<?php

declare(strict_types=1);

namespace UniversalMime\Wire\Header;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['5322', 'Header Field Syntax, Folding and Unfolding'],
])]
final class HeaderCodec
{
    /**
     * Unfolding RFC 5322:
     *   obs-fold = CRLF *( WSP )
     * On recolle les lignes continuées en remplaçant le CRLF + WSP par un espace simple.
     *
     * @param RawHeaderLine[] $lines
     */
    public function unfold(array $lines): string
    {
        if (count($lines) === 0) {
            return '';
        }

        // On prend la première ligne sans CRLF
        $buffer = $lines[0]->withoutCrlf();

        // On traite les lignes suivantes (continuations)
        $count = count($lines);
        for ($i = 1; $i < $count; $i++) {
            $line = $lines[$i];

            if ($line->isContinuation()) {
                // On remplace CRLF + WSP par un espace
                $buffer .= ' ' . ltrim($line->withoutCrlf());
            } else {
                // Si on tombe sur une ligne non continuation => error logique
                // mais on ne jette pas d'exception ici, le parser gère
                $buffer .= ' ' . $line->withoutCrlf();
            }
        }

        return $buffer;
    }

    /**
     * Split name:value selon RFC 5322.
     *
     * - Le premier ":" sépare le nom et la valeur.
     * - Tout avant ":" doit être un field-name valide (ASCII, pas d'espace).
     * - La value peut contenir des ":" validement.
     *
     * @return array{name:string, value:string}
     */
    public function splitField(string $unfolded): array
    {
        $pos = strpos($unfolded, ':');

        if ($pos === false) {
            // Pas de ":", ligne invalide mais on la laisse brute
            return [
                'name' => $unfolded,
                'value' => '',
            ];
        }

        $name = substr($unfolded, 0, $pos);
        $value = substr($unfolded, $pos + 1);

        // Trim RFC: leading WSP is removed
        $value = ltrim($value);

        return [
            'name' => $name,
            'value' => $value,
        ];
    }
}

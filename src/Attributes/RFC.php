<?php

declare(strict_types=1);

namespace UniversalMime\Attributes;

use Attribute;

/**
 * Permet d'annoter une classe avec une ou plusieurs RFC,
 * chaque entrée contenant le numéro et un titre descriptif.
 *
 * Exemple :
 * #[RFC([
 *     ['2045', 'MIME Format'],
 *     ['2046', 'Media Types'],
 * ])]
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
final class RFC
{
    /**
     * @param array<int, array{0:string, 1:string}> $rfcs
     *        format : [ [numero, titre], ... ]
     */
    public function __construct(
        public array $rfcs,
    ) {
    }
}

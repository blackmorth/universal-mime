<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

use UniversalMime\Parser\Stream\StreamInterface;

interface TransferDecoderInterface
{
    /**
     * Prend un flux brut (encodé) et retourne un flux décodé.
     *
     * Le résultat DOIT être un StreamInterface lisible.
     */
    public function decode(StreamInterface $encoded): StreamInterface;
}

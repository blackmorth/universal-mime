<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

use UniversalMime\Attributes\RFC;
use UniversalMime\Attributes\TransferEncoding;
use UniversalMime\Parser\Stream\StreamInterface;

/**
 * Identity decoder pour 7bit / 8bit / binary (RFC 2045 §2.8).
 * Ne fait aucune transformation, renvoie le flux d'entrée tel quel.
 */
#[RFC([
    ['2045', 'MIME: 7bit / 8bit / binary identity encodings (section 2.8)'],
])]
#[TransferEncoding('7bit')]
#[TransferEncoding('8bit')]
#[TransferEncoding('binary')]
final class IdentityStreamDecoder implements TransferDecoderInterface
{
    public function decode(StreamInterface $encoded): StreamInterface
    {
        // Identity: pas de modification, on renvoie le stream tel quel.
        return $encoded;
    }
}

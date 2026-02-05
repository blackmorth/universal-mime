<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

use UniversalMime\Attributes\RFC;
use UniversalMime\Attributes\TransferEncoding;
use UniversalMime\Parser\Stream\StreamInterface;
use UniversalMime\Parser\Stream\MemoryStream;

/**
 * Décode un flux Base64 (RFC 2045 §6.8).
 * Version simple : lit le flux complet, nettoie les CRLF, décode base64.
 * Compatible avec les attachments email, SIP/CPIM, 3GPP, etc.
 */
#[RFC([
    ['2045', 'MIME Base64 Content-Transfer-Encoding (section 6.8)']
])]
#[TransferEncoding('base64')]
final class Base64StreamDecoder implements TransferDecoderInterface
{
    public function decode(StreamInterface $encoded): StreamInterface
    {
        $buffer = '';

        // Lire tout le flux encodé (Base64 nécessite le bloc complet)
        while (!$encoded->eof()) {
            $chunk = $encoded->read(8192);
            if ($chunk === null) {
                break;
            }
            $buffer .= $chunk;
        }

        // RFC 2045 §6.8: characters outside Base64 alphabet are ignored.
        $clean = preg_replace('/[^A-Za-z0-9+\/=]/', '', $buffer);

        if ($clean === null) {
            return new MemoryStream('');
        }

        // Lenient recovery for inputs with missing "=" padding.
        $remainder = strlen($clean) % 4;
        if ($remainder !== 0) {
            $clean .= str_repeat('=', 4 - $remainder);
        }

        // Décodage RFC 4648 (compatible RFC 2045)
        $decoded = base64_decode($clean, true);

        if ($decoded === false || $decoded === '') {
            $decoded = '';
        }

        return new MemoryStream($decoded);
    }
}

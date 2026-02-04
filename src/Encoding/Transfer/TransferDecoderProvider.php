<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

interface TransferDecoderProvider
{
    /**
     * Returns a decoder for a given encoding name
     * ("base64", "quoted-printable", "gzip", "7bit", etc.)
     *
     * MUST return null when no decoder exists.
     */
    public function get(string $encoding): ?TransferDecoderInterface;
}

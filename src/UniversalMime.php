<?php

declare(strict_types=1);

namespace UniversalMime;

use UniversalMime\Encoding\Transfer\Base64StreamDecoder;
use UniversalMime\Encoding\Transfer\GzipStreamDecoder;
use UniversalMime\Encoding\Transfer\IdentityStreamDecoder;
use UniversalMime\Encoding\Transfer\QPStreamDecoder;
use UniversalMime\Parser\MimeParser;
use UniversalMime\Parser\HeaderParser;
use UniversalMime\Encoding\Transfer\TransferEncodingRegistry;

final class UniversalMime
{
    public static function defaultParser(): MimeParser
    {
        return new MimeParser(
            new HeaderParser(),
            new TransferEncodingRegistry([
                Base64StreamDecoder::class,
                QPStreamDecoder::class,
                IdentityStreamDecoder::class,
                GzipStreamDecoder::class,
            ])
        );
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Encoding\Transfer\Base64StreamDecoder;
use UniversalMime\Encoding\Transfer\GzipStreamDecoder;
use UniversalMime\Encoding\Transfer\IdentityStreamDecoder;
use UniversalMime\Encoding\Transfer\QPStreamDecoder;
use UniversalMime\Encoding\Transfer\TransferEncodingRegistry;

final class ParserFactory
{
    public static function message(): MessageStreamParser
    {
        $startLineParser = new StartLineParser();
        $headerParser = new HeaderParser();
        $registry = new TransferEncodingRegistry([
            Base64StreamDecoder::class,
            QPStreamDecoder::class,
            IdentityStreamDecoder::class,
            GzipStreamDecoder::class,
        ]);
        $mimeParser = new MimeParser($headerParser, $registry);

        return new MessageStreamParser(
            $startLineParser,
            $headerParser,
            $registry,
            $mimeParser
        );
    }
}

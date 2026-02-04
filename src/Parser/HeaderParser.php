<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Attributes\RFC;
use UniversalMime\Model\Header;
use UniversalMime\Model\HeaderBag;
use UniversalMime\Wire\Header\RawHeaderBlock;
use UniversalMime\Wire\Header\HeaderCodec;

/**
 * Transforme un RawHeaderBlock unfoldé en HeaderBag immuable.
 * Conforme RFC 5322 : field-name ":" unstructured
 */
#[RFC([
    ['5322', 'Header Field Parsing'],
])]
final class HeaderParser implements HeaderParserInterface
{
    public function __construct(private ?HeaderCodec $codec = null)
    {
        $this->codec = $codec ?? new HeaderCodec();
    }

    public function parse(RawHeaderBlock $block): HeaderBag
    {
        $fields = $block->unfoldedFields();
        $headers = [];

        foreach ($fields as $line) {
            if ($line === '') {
                continue;
            }

            $res = $this->codec->splitField($line);

            $name  = trim($res['name']);
            $value = $res['value'];

            if ($name === '') {
                continue;
            }

            if (!$this->isValidFieldName($name)) {
                continue;
            }

            $headers[] = new Header($name, $value);
        }

        return new HeaderBag($headers);
    }

    private function isValidFieldName(string $name): bool
    {
        return (bool)preg_match('/^[A-Za-z0-9\-]+$/D', $name);
    }
}

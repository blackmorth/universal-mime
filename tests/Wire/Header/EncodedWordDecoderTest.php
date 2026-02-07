<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Wire\Header;

use PHPUnit\Framework\TestCase;
use UniversalMime\Wire\Header\EncodedWordDecoder;

final class EncodedWordDecoderTest extends TestCase
{
    public function testDecodesEncodedWords(): void
    {
        $value = 'Hello =?UTF-8?B?V29ybGQ=?=';

        $this->assertSame('Hello World', EncodedWordDecoder::decode($value));
    }

    public function testDecodesQEncodingAndIgnoresWhitespaceBetweenWords(): void
    {
        $value = '=?UTF-8?Q?Bonjour?= =?UTF-8?Q?_Monde?=';

        $this->assertSame('Bonjour Monde', EncodedWordDecoder::decode($value));
    }

    public function testKeepsNonEncodedText(): void
    {
        $value = 'Subject: plain text';

        $this->assertSame($value, EncodedWordDecoder::decode($value));
    }
}

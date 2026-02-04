<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Encoding\Transfer;

use PHPUnit\Framework\TestCase;
use UniversalMime\Encoding\Transfer\Base64StreamDecoder;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Wire\Line;

final class Base64StreamDecoderTest extends TestCase
{
    public function testDecodeSimpleBase64(): void
    {
        $decoder = new Base64StreamDecoder();
        $stream = new MemoryStream(base64_encode("Hello World"));

        $decoded = $decoder->decode($stream);

        $this->assertSame("Hello World", $decoded->read(9999));
    }

    public function testDecodeWithCRLFAndWhitespace(): void
    {
        $input =
            "SGVsbG8g" . Line::CRLF .
            "V29ybGQ=" . Line::CRLF;

        $decoder = new Base64StreamDecoder();
        $stream = new MemoryStream($input);

        $decoded = $decoder->decode($stream);
        $this->assertSame("Hello World", $decoded->read(999));
    }

    public function testInvalidBase64ReturnsEmptyStream(): void
    {
        $decoder = new Base64StreamDecoder();
        $stream = new MemoryStream("%%%");

        $decoded = $decoder->decode($stream);
        $this->assertSame("", $decoded->read(999));
    }
}

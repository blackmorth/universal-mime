<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use UniversalMime\Encoding\Transfer\QPStreamDecoder;
use UniversalMime\Parser\Stream\MemoryStream;

final class QPStreamDecoderTest extends TestCase
{
    public function testDecodeSimpleQP(): void
    {
        $input = "Bonjour=20Monde";
        $decoder = new QPStreamDecoder();

        $decoded = $decoder->decode(new MemoryStream($input));

        $this->assertSame("Bonjour Monde", $decoded->read(999));
    }

    public function testDecodeUtf8QP(): void
    {
        $input = "Caf=C3=A9";
        $decoder = new QPStreamDecoder();

        $decoded = $decoder->decode(new MemoryStream($input));

        $this->assertSame("Café", $decoded->read(999));
    }

    public function testSoftLineBreak(): void
    {
        $input = "Coucou=\r\nMonde";
        $decoder = new QPStreamDecoder();

        $decoded = $decoder->decode(new MemoryStream($input));

        $this->assertSame("CoucouMonde", $decoded->read(999));
    }

    public function testTrimTrailingWhitespace(): void
    {
        $input = "Hello \t =20 \r\nWorld";
        $decoder = new QPStreamDecoder();

        $decoded = $decoder->decode(new MemoryStream($input));
        $this->assertSame("Hello =20\nWorld", $decoded->read(999));
    }
}

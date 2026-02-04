<?php

use PHPUnit\Framework\TestCase;
use UniversalMime\Encoding\Transfer\GzipStreamDecoder;
use UniversalMime\Parser\Stream\MemoryStream;

final class GzipStreamDecoderTest extends TestCase
{
    public function testValidGzip(): void
    {
        $input = "Hello GZIP World";
        $gz = gzencode($input);

        $decoder = new GzipStreamDecoder();
        $stream = $decoder->decode(new MemoryStream($gz));

        $this->assertSame($input, $stream->read(999));
    }

    public function testInvalidGzipReturnsEmptyString(): void
    {
        $decoder = new GzipStreamDecoder();
        $stream = $decoder->decode(new MemoryStream("NOT_GZIP"));

        $this->assertSame("", $stream->read(999));
    }
}

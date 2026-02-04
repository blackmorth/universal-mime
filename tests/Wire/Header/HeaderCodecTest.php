<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Wire\Header;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Attributes\RFC;
use UniversalMime\Wire\Header\RawHeaderLine;
use UniversalMime\Wire\Header\HeaderCodec;
use UniversalMime\Wire\Line;

final class HeaderCodecTest extends TestCase
{
    public function testUnfoldingSimple(): void
    {
        $codec = new HeaderCodec();

        $lines = [
            new RawHeaderLine("Subject: Hello" . Line::CRLF),
            new RawHeaderLine(" World" . Line::CRLF),
        ];

        $out = $codec->unfold($lines);

        $this->assertSame("Subject: Hello World", $out);
    }

    public function testSplitField(): void
    {
        $codec = new HeaderCodec();

        $res = $codec->splitField("Content-Type: text/plain; charset=utf-8");

        $this->assertSame("Content-Type", $res['name']);
        $this->assertSame("text/plain; charset=utf-8", $res['value']);
    }

    public function testSplitFieldWithoutValue(): void
    {
        $codec = new HeaderCodec();

        $res = $codec->splitField("X-Test:");

        $this->assertSame("X-Test", $res['name']);
        $this->assertSame("", $res['value']);
    }

    public function testUnfoldingMultipleContinuations(): void
    {
        $codec = new HeaderCodec();

        $lines = [
            new RawHeaderLine("List: a," . Line::CRLF),
            new RawHeaderLine(" b," . Line::CRLF),
            new RawHeaderLine("\t c" . Line::CRLF),
        ];

        $out = $codec->unfold($lines);

        $this->assertSame("List: a, b, c", $out);
    }

    public function testRFCAttributePresent(): void
    {
        $ref = new ReflectionClass(HeaderCodec::class);

        $attrs = $ref->getAttributes(RFC::class);
        $this->assertCount(1, $attrs);

        $rfc = $attrs[0]->newInstance();
        $this->assertSame('5322', $rfc->rfcs[0][0]);
    }
}

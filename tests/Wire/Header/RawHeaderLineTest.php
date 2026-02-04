<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Wire\Header;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Attributes\RFC;
use UniversalMime\Wire\Header\RawHeaderLine;
use UniversalMime\Wire\Line;

final class RawHeaderLineTest extends TestCase
{
    public function testStoresRawLine(): void
    {
        $line = new RawHeaderLine("Content-Type: text/plain" . Line::CRLF);
        $this->assertSame("Content-Type: text/plain" . Line::CRLF, $line->raw);
    }

    public function testWithoutCrlfRemovesOnlyTrailingCrlf(): void
    {
        $line = new RawHeaderLine("X-Test: ok" . Line::CRLF);
        $this->assertSame("X-Test: ok", $line->withoutCrlf());
    }

    public function testContinuationDetection(): void
    {
        $line1 = new RawHeaderLine(" folded continuation" . Line::CRLF);
        $this->assertTrue($line1->isContinuation());

        $line2 = new RawHeaderLine("\tfolded too" . Line::CRLF);
        $this->assertTrue($line2->isContinuation());

        $line3 = new RawHeaderLine("Content-Type: text/plain" . Line::CRLF);
        $this->assertFalse($line3->isContinuation());
    }

    public function testEmptyLineIsNotContinuation(): void
    {
        $line = new RawHeaderLine(Line::CRLF);
        $this->assertFalse($line->isContinuation());
    }

    public function testRFCAttributeIsPresent(): void
    {
        $ref = new ReflectionClass(RawHeaderLine::class);

        $attrs = $ref->getAttributes(RFC::class);
        $this->assertCount(1, $attrs);

        $rfc = $attrs[0]->newInstance();

        $this->assertSame('5322', $rfc->rfcs[0][0]);
    }
}

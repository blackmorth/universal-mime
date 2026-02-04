<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Wire\Header;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Attributes\RFC;
use UniversalMime\Wire\Header\RawHeaderLine;
use UniversalMime\Wire\Header\RawHeaderBlock;
use UniversalMime\Wire\Line;

final class RawHeaderBlockTest extends TestCase
{
    public function testCollectsLinesAndDetectsCompletion(): void
    {
        $block = new RawHeaderBlock();

        $block->addLine(new RawHeaderLine("Subject: Test" . Line::CRLF));
        $block->addLine(new RawHeaderLine(Line::CRLF));

        $this->assertTrue($block->isComplete());
        $this->assertCount(2, $block->rawLines());
    }

    public function testGroupsLinesCorrectly(): void
    {
        $block = new RawHeaderBlock();

        $block->addLine(new RawHeaderLine("Subject: Hello" . Line::CRLF));
        $block->addLine(new RawHeaderLine(" World" . Line::CRLF));
        $block->addLine(new RawHeaderLine("X-Test: ok" . Line::CRLF));
        $block->addLine(new RawHeaderLine(Line::CRLF));

        $fields = $block->unfoldedFields();

        $this->assertSame("Subject: Hello World", $fields[0]);
        $this->assertSame("X-Test: ok", $fields[1]);
    }

    public function testRFCAttributePresent(): void
    {
        $ref = new ReflectionClass(RawHeaderBlock::class);

        $attrs = $ref->getAttributes(RFC::class);
        $this->assertCount(1, $attrs);

        $rfc = $attrs[0]->newInstance();
        $this->assertSame('5322', $rfc->rfcs[0][0]);
    }
}

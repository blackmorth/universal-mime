<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Attributes\RFC;
use UniversalMime\Parser\HeaderParser;
use UniversalMime\Wire\Header\RawHeaderLine;
use UniversalMime\Wire\Header\RawHeaderBlock;
use UniversalMime\Wire\Line;

final class HeaderParserTest extends TestCase
{
    public function testParsesSimpleHeaderBlock(): void
    {
        $block = new RawHeaderBlock();

        $block->addLine(new RawHeaderLine("Subject: Hello World" . Line::CRLF));
        $block->addLine(new RawHeaderLine("X-Test: ok" . Line::CRLF));
        $block->addLine(new RawHeaderLine(Line::CRLF));

        $parser = new HeaderParser();
        $bag = $parser->parse($block);

        $this->assertSame("Hello World", $bag->get("Subject")->value);
        $this->assertSame("ok", $bag->get("X-Test")->value);
    }

    public function testParsesFoldedHeader(): void
    {
        $block = new RawHeaderBlock();

        $block->addLine(new RawHeaderLine("Subject: Hello" . Line::CRLF));
        $block->addLine(new RawHeaderLine(" World" . Line::CRLF));
        $block->addLine(new RawHeaderLine(Line::CRLF));

        $parser = new HeaderParser();
        $bag = $parser->parse($block);

        $this->assertSame("Hello World", $bag->get("Subject")->value);
    }

    public function testDecodesEncodedWordsInHeaderValues(): void
    {
        $block = new RawHeaderBlock();

        $block->addLine(new RawHeaderLine("Subject: =?UTF-8?Q?Bonjour?= =?UTF-8?Q?_Monde?=" . Line::CRLF));
        $block->addLine(new RawHeaderLine(Line::CRLF));

        $parser = new HeaderParser();
        $bag = $parser->parse($block);

        $this->assertSame("Bonjour Monde", $bag->get("Subject")->value);
    }

    public function testInvalidHeaderNamesAreIgnored(): void
    {
        $block = new RawHeaderBlock();

        $block->addLine(new RawHeaderLine("Bad Header: nope" . Line::CRLF));
        $block->addLine(new RawHeaderLine("Ok: yes" . Line::CRLF));
        $block->addLine(new RawHeaderLine(Line::CRLF));

        $parser = new HeaderParser();
        $bag = $parser->parse($block);

        // "Bad Header" contient un espace -> invalid RFC field-name
        $this->assertNull($bag->get("Bad Header"));
        $this->assertSame("yes", $bag->get("Ok")->value);
    }

    public function testRFCAttributePresent(): void
    {
        $ref = new ReflectionClass(HeaderParser::class);

        $attrs = $ref->getAttributes(RFC::class);
        $this->assertCount(1, $attrs);

        $rfc = $attrs[0]->newInstance();
        $this->assertSame('5322', $rfc->rfcs[0][0]);
    }
}

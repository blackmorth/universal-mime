<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Attributes\RFC;
use UniversalMime\Parser\StartLineParser;

final class StartLineParserTest extends TestCase
{
    public function testParsesHttpRequest(): void
    {
        $parser = new StartLineParser();
        $sl = $parser->parse("GET /index.html HTTP/1.1");

        $this->assertSame("GET /index.html HTTP/1.1", $sl->raw);
        $this->assertSame("request", $sl->type);
    }

    public function testParsesHttpResponse(): void
    {
        $parser = new StartLineParser();
        $sl = $parser->parse("HTTP/1.1 200 OK");

        $this->assertSame("response", $sl->type);
    }

    public function testParsesSipRequest(): void
    {
        $parser = new StartLineParser();
        $sl = $parser->parse("INVITE sip:bob@localhost SIP/2.0");

        $this->assertSame("request", $sl->type);
    }

    public function testParsesSipResponse(): void
    {
        $parser = new StartLineParser();
        $sl = $parser->parse("SIP/2.0 486 Busy Here");

        $this->assertSame("response", $sl->type);
    }

    public function testUnknownStartLine(): void
    {
        $parser = new StartLineParser();
        $sl = $parser->parse("Something odd");

        $this->assertSame("unknown", $sl->type);
    }

    public function testRFCAttributePresent(): void
    {
        $ref = new ReflectionClass(StartLineParser::class);

        $attrs = $ref->getAttributes(RFC::class);
        $this->assertCount(1, $attrs);

        $rfc = $attrs[0]->newInstance();
        $this->assertSame('7230', $rfc->rfcs[0][0]);
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser;

use PHPUnit\Framework\TestCase;
use UniversalMime\Parser\ParserFactory;
use UniversalMime\Parser\Stream\ResourceStream;
use UniversalMime\Wire\Line;

final class MessageStreamParserTest extends TestCase
{
    public function testParsesSimpleHttpMessage(): void
    {
        $raw =
            "GET /index.html HTTP/1.1" . Line::CRLF .
            "Host: example.com" . Line::CRLF .
            "X-Test: ok" . Line::CRLF .
            Line::CRLF .
            "BODY";

        $stream = new ResourceStream(fopen('php://memory', 'r+'));
        $stream->write($raw);
        $stream->rewind();

        $parser = ParserFactory::message();
        $msg = $parser->parse($stream);

        $this->assertSame("request", $msg->startLine?->type);
        $this->assertSame("ok", $msg->headers->get("X-Test")->value);
    }
}

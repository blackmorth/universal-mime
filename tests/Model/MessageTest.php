<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\Message;
use UniversalMime\Model\Part;
use UniversalMime\Model\StartLine;
use UniversalMime\Model\HeaderBag;
use UniversalMime\Model\Header;
use UniversalMime\Model\Body;
use UniversalMime\Parser\Stream\MemoryStream;

final class MessageTest extends TestCase
{
    public function testMessageBasics(): void
    {
        $start = new StartLine("GET / HTTP/1.1");
        $headers = new HeaderBag([new Header("A", "1")]);

        $body = new Body(new MemoryStream("Hello World!"));

        $message = new Message($start, $headers, $body);

        $this->assertSame($start, $message->startLine);
        $this->assertSame($headers, $message->headers);
        $this->assertSame($body, $message->body);
        $this->assertSame([], $message->parts);
    }

    public function testMultipartMessage(): void
    {
        $headers = new HeaderBag([]);
        $start = null;

        $part1 = new Part($headers, new Body(new MemoryStream("Hello World!")));

        $msg = new Message($start, $headers, body: null, parts: [$part1]);

        $this->assertNull($msg->body);
        $this->assertCount(1, $msg->parts);
        $this->assertSame($part1, $msg->parts[0]);
    }
}

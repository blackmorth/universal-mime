<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\Body;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Parser\Stream\StreamInterface;

final class BodyTest extends TestCase
{
    public function testBodyStoresStream(): void
    {
        $stream = new MemoryStream("ABC");

        $body = new Body($stream);

        $this->assertSame($stream, $body->stream);
        $this->assertFalse($body->isBuffered());
    }

    public function testBodyBuffered(): void
    {
        $stream = new MemoryStream("Hello");

        $body = new Body($stream, cached: "Hello");

        $this->assertTrue($body->isBuffered());
        $this->assertSame("Hello", $body->cached);
    }
}

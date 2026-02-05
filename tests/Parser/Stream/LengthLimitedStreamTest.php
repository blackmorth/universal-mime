<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser\Stream;

use PHPUnit\Framework\TestCase;
use UniversalMime\Parser\Stream\LengthLimitedStream;
use UniversalMime\Parser\Stream\MemoryStream;

final class LengthLimitedStreamTest extends TestCase
{
    public function testReadRespectsLimit(): void
    {
        $inner = new MemoryStream('abcdef');
        $stream = new LengthLimitedStream($inner, 3);

        $this->assertSame('ab', $stream->read(2));
        $this->assertSame('c', $stream->read(2));
        $this->assertNull($stream->read(1));
        $this->assertSame('def', $inner->read(10));
    }

    public function testReadLinePushesBackOverflow(): void
    {
        $inner = new MemoryStream("hello\nworld\n");
        $stream = new LengthLimitedStream($inner, 3);

        $this->assertSame('hel', $stream->readLine());
        $this->assertNull($stream->readLine());
        $this->assertSame("lo\n", $inner->readLine());
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser\Stream;

use PHPUnit\Framework\TestCase;
use UniversalMime\Parser\Stream\ChunkedStream;
use UniversalMime\Parser\Stream\MemoryStream;

final class ChunkedStreamTest extends TestCase
{
    public function testDelegatesReadAndUnshift(): void
    {
        $inner = new MemoryStream("abc\ndef\n");
        $stream = new ChunkedStream($inner);

        $this->assertSame("abc\n", $stream->readLine());

        $stream->unshift("abc\n");
        $this->assertSame("abc\n", $stream->readLine());
        $this->assertSame("def\n", $stream->readLine());
    }
}

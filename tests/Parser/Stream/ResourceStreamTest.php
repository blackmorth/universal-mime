<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Parser\Stream;

use PHPUnit\Framework\TestCase;
use UniversalMime\Parser\Stream\ResourceStream;

final class ResourceStreamTest extends TestCase
{
    public function testReadLineConsumesPushbackAndHandle(): void
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "world\nnext\n");
        rewind($handle);

        $stream = new ResourceStream($handle);
        $stream->unshift('hello ');

        $this->assertSame("hello world\n", $stream->readLine());
        $this->assertSame("next\n", $stream->readLine());
    }

    public function testReadCombinesPushbackWithHandle(): void
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, 'abcdef');
        rewind($handle);

        $stream = new ResourceStream($handle);
        $stream->unshift('12');

        $this->assertSame('12ab', $stream->read(4));
        $this->assertSame('cdef', $stream->read(10));
    }
}

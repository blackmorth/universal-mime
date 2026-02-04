<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Encoding\Transfer;

use PHPUnit\Framework\TestCase;
use UniversalMime\Encoding\Transfer\IdentityStreamDecoder;
use UniversalMime\Parser\Stream\MemoryStream;

final class IdentityStreamDecoderTest extends TestCase
{
    public function testIdentityDecoderReturnsSameStream(): void
    {
        $stream = new MemoryStream("Hello World");
        $decoder = new IdentityStreamDecoder();

        $decoded = $decoder->decode($stream);

        // Même instance
        $this->assertSame($stream, $decoded);

        // Et même contenu
        $decoded->rewind();
        $this->assertSame("Hello World", $decoded->read(999));
    }
}

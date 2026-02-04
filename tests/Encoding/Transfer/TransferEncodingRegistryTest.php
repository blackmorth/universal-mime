<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Encoding\Transfer;

use PHPUnit\Framework\TestCase;
use UniversalMime\Attributes\TransferEncoding;
use UniversalMime\Encoding\Transfer\TransferDecoderInterface;
use UniversalMime\Encoding\Transfer\TransferEncodingRegistry;
use UniversalMime\Parser\Stream\MemoryStream;
use UniversalMime\Parser\Stream\StreamInterface;

#[TransferEncoding('dummy')]
final class DummyDecoder implements TransferDecoderInterface
{
    public function decode(StreamInterface $encoded): StreamInterface
    {
        return new MemoryStream("decoded");
    }
}

final class TransferEncodingRegistryTest extends TestCase
{
    public function testRegisterAndGet(): void
    {
        $registry = new TransferEncodingRegistry([
            DummyDecoder::class,
        ]);

        $decoder = $registry->get('dummy');
        $this->assertInstanceOf(TransferDecoderInterface::class, $decoder);

        $result = $decoder->decode(new MemoryStream("xxx"));
        $this->assertSame("decoded", $result->read(999));
    }
}

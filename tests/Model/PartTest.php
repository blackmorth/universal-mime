<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\Header;
use UniversalMime\Model\HeaderBag;
use UniversalMime\Model\Body;
use UniversalMime\Model\Part;
use UniversalMime\Parser\Stream\MemoryStream;

final class PartTest extends TestCase
{
    public function testPartStoresHeadersAndBody(): void
    {
        $bag = new HeaderBag([new Header("A", "1")]);
        $stream = new MemoryStream("");
        $body = new Body($stream);

        $part = new Part($bag, $body);

        $this->assertSame($bag, $part->headers);
        $this->assertSame($body, $part->body);
        $this->assertSame([], $part->children);
    }

    public function testPartWithChildren(): void
    {
        $bag = new HeaderBag([]);
        $body = new Body(new MemoryStream(""));

        $child = new Part($bag, $body);
        $parent = new Part($bag, $body, [$child]);

        $this->assertSame([$child], $parent->children);
    }
}

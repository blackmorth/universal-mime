<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\Header;
use UniversalMime\Model\HeaderBag;

final class HeaderBagTest extends TestCase
{
    public function testHeaderBagIndexesHeadersByLowerName(): void
    {
        $h1 = new Header("Content-Type", "text/plain");
        $h2 = new Header("CONTENT-TYPE", "application/json");

        $bag = new HeaderBag([$h1, $h2]);

        $this->assertSame($h1, $bag->get("Content-Type"));
        $this->assertSame($h1, $bag->get("content-type"));
        $this->assertSame([$h1, $h2], $bag->all("content-type"));
    }

    public function testHeaderBagIsImmutable(): void
    {
        $h1 = new Header("A", "1");
        $bag = new HeaderBag([$h1]);

        $out = $bag->headers();
        $out[] = new Header("B", "2");

        // Le bag ne doit pas bouger
        $this->assertCount(1, $bag);
    }

    public function testIteratorWorks(): void
    {
        $h1 = new Header("A", "1");
        $h2 = new Header("B", "2");

        $bag = new HeaderBag([$h1, $h2]);
        $collected = [];

        foreach ($bag as $h) {
            $collected[] = $h;
        }

        $this->assertSame([$h1, $h2], $collected);
    }
}

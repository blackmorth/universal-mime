<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use UniversalMime\Attributes\RFC;

final class RFCTest extends TestCase
{
    public function testStoresMultipleRFCs(): void
    {
        $attr = new RFC([
            ['2045', 'MIME Format'],
            ['2046', 'Media Types'],
            ['2231', 'Extended Parameters'],
        ]);

        $this->assertCount(3, $attr->rfcs);

        $this->assertSame(['2045', 'MIME Format'], $attr->rfcs[0]);
        $this->assertSame(['2046', 'Media Types'], $attr->rfcs[1]);
        $this->assertSame(['2231', 'Extended Parameters'], $attr->rfcs[2]);
    }

    public function testRFCStructureIsStrict(): void
    {
        $attr = new RFC([
            ['6838', 'Media Type Registration'],
        ]);

        $this->assertIsArray($attr->rfcs);
        $this->assertSame('6838', $attr->rfcs[0][0]);
        $this->assertSame('Media Type Registration', $attr->rfcs[0][1]);
    }
}

<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\Header;

final class HeaderTest extends TestCase
{
    public function testHeaderStoresNameAndValue(): void
    {
        $h = new Header("Content-Type", "application/json");

        $this->assertSame("Content-Type", $h->name);
        $this->assertSame("application/json", $h->value);
    }

    public function testLowerName(): void
    {
        $h = new Header("CONTENT-TYPE", "text/plain");
        $this->assertSame("content-type", $h->lowerName());
    }
}

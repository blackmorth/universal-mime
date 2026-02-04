<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\HeaderValue;

final class HeaderValueTest extends TestCase
{
    public function testStoresRawValue(): void
    {
        $hv = new HeaderValue("text/plain; charset=utf-8");

        $this->assertSame("text/plain; charset=utf-8", $hv->raw);
        $this->assertSame("text/plain; charset=utf-8", (string)$hv);
    }
}

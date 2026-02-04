<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use UniversalMime\Attributes\HeaderRule;

final class HeaderRuleTest extends TestCase
{
    public function testStoresHeaderInfo(): void
    {
        $attr = new HeaderRule('Content-Type', required: true);

        $this->assertSame('Content-Type', $attr->name);
        $this->assertTrue($attr->required);
    }
}

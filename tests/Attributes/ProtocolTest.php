<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Attributes\Protocol;
use UniversalMime\Parser\StartLineParser;

final class ProtocolTest extends TestCase
{
    public function testProtocolAttributePresent(): void
    {
        $ref = new ReflectionClass(StartLineParser::class);

        $attrs = $ref->getAttributes(Protocol::class);
        $this->assertCount(1, $attrs);

        $instance = $attrs[0]->newInstance();

        $this->assertSame(['HTTP', 'SIP'], $instance->protocols);
    }
}

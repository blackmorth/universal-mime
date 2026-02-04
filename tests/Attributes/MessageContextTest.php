<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use UniversalMime\Attributes\MessageContext;

final class MessageContextTest extends TestCase
{
    public function testStoresProtocols(): void
    {
        $attr = new MessageContext(['sip', 'http'], priority: 10);

        $this->assertSame(['sip', 'http'], $attr->protocols);
        $this->assertSame(10, $attr->priority);
    }
}

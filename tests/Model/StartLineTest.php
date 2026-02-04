<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\StartLine;

final class StartLineTest extends TestCase
{
    public function testStartLineStoresRawValue(): void
    {
        $sl = new StartLine("INVITE sip:alice@example.com SIP/2.0");

        $this->assertSame(
            "INVITE sip:alice@example.com SIP/2.0",
            $sl->raw
        );
    }
}

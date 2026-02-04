<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use UniversalMime\Attributes\TransferEncoding;

final class TransferEncodingTest extends TestCase
{
    public function testStoresEncodingName(): void
    {
        $attr = new TransferEncoding('base64');
        $this->assertSame('base64', $attr->encoding);
    }
}

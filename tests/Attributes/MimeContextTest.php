<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use UniversalMime\Attributes\MimeContext;

final class MimeContextTest extends TestCase
{
    public function testStoresMimeTypesAndPriority(): void
    {
        $attr = new MimeContext(['application/json', 'text/*'], priority: 50);

        $this->assertSame(['application/json', 'text/*'], $attr->mimeTypes);
        $this->assertSame(50, $attr->priority);
    }
}

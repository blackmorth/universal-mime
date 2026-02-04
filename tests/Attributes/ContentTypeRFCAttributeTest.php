<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Attributes;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UniversalMime\Model\ContentType;
use UniversalMime\Attributes\RFC;

final class ContentTypeRFCAttributeTest extends TestCase
{
    public function testContentTypeHasRFCAttribute(): void
    {
        $ref = new ReflectionClass(ContentType::class);
        $attrs = $ref->getAttributes(RFC::class);

        $this->assertCount(1, $attrs);

        $instance = $attrs[0]->newInstance();

        $this->assertNotEmpty($instance->rfcs);
        $this->assertSame('2045', $instance->rfcs[0][0]);
    }
}

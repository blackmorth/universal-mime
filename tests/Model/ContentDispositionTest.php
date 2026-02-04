<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\ContentDisposition;
use UniversalMime\Model\Parameter;

final class ContentDispositionTest extends TestCase
{
    public function testDispositionType(): void
    {
        $cd = new ContentDisposition("attachment");
        $this->assertSame("attachment", $cd->type());
    }

    public function testDispositionParameters(): void
    {
        $p = new Parameter("filename", "a.png");
        $cd = new ContentDisposition("attachment", [$p]);

        $this->assertSame($p, $cd->getParam("filename"));
        $this->assertNull($cd->getParam("unknown"));
    }
}

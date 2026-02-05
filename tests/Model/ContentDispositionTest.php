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

    public function testDispositionFromHeaderValueWithExtendedFilename(): void
    {
        $cd = ContentDisposition::fromHeaderValue(
            "attachment; filename*=utf-8'en'%E2%82%AC%20rates.pdf"
        );

        $filename = $cd->getParam('filename');
        $this->assertSame('€ rates.pdf', $filename->value);
        $this->assertSame('utf-8', $filename->charset);
        $this->assertSame('en', $filename->language);
    }

    public function testDispositionFromHeaderValueWithContinuation(): void
    {
        $cd = ContentDisposition::fromHeaderValue(
            "attachment; filename*0*=utf-8''hello%20; filename*1*=world.txt"
        );

        $filename = $cd->getParam('filename');
        $this->assertSame('hello world.txt', $filename->value);
        $this->assertSame('utf-8', $filename->charset);
        $this->assertNull($filename->language);
    }
}

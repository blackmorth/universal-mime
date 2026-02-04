<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\Parameter;

final class ParameterTest extends TestCase
{
    public function testBasicParameter(): void
    {
        $p = new Parameter("charset", "utf-8");

        $this->assertSame("charset", $p->name);
        $this->assertSame("utf-8", $p->value);
        $this->assertFalse($p->hasExtended());
        $this->assertSame("charset", $p->lowerName());
    }

    public function testExtendedParameter(): void
    {
        $p = new Parameter("filename", "report.pdf", charset: "utf-8", language: "en");

        $this->assertTrue($p->hasExtended());
        $this->assertSame("utf-8", $p->charset);
        $this->assertSame("en", $p->language);
    }
}

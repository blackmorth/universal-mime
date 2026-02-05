<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\ContentType;
use UniversalMime\Model\Parameter;

final class ContentTypeTest extends TestCase
{
    public function testBasicContentType(): void
    {
        $ct = new ContentType("application", "json");

        $this->assertSame("application", $ct->type);
        $this->assertSame("json", $ct->subtype);
        $this->assertSame("application/json", $ct->mime());
    }

    public function testContentTypeWithSuffix(): void
    {
        $ct = new ContentType("application", "soap", "xml");

        $this->assertSame("application/soap+xml", $ct->mime());
    }

    public function testContentTypeWithParameters(): void
    {
        $charset = new Parameter("charset", "utf-8");
        $boundary = new Parameter("boundary", "XYZ");

        $ct = new ContentType("multipart", "mixed", null, [$charset, $boundary]);

        $this->assertTrue($ct->hasParam("charset"));
        $this->assertSame($charset, $ct->getParam("charset"));

        $this->assertTrue($ct->hasParam("boundary"));
    }

    public function testContentTypeFromHeaderValue(): void
    {
        $ct = ContentType::fromHeaderValue('application/soap+xml; charset="utf-8"; boundary=abc');

        $this->assertSame('application', $ct->type);
        $this->assertSame('soap', $ct->subtype);
        $this->assertSame('xml', $ct->suffix);
        $this->assertSame('utf-8', $ct->getParam('charset')->value);
        $this->assertSame('abc', $ct->getParam('boundary')->value);
    }
}

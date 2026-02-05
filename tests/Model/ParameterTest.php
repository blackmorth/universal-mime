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

    public function testParseParametersWithQuotedValues(): void
    {
        $params = Parameter::parseParameters(['charset="utf-8"', 'name="a\\\\b"']);

        $charset = $this->getParamByName($params, 'charset');
        $name = $this->getParamByName($params, 'name');

        $this->assertSame('utf-8', $charset->value);
        $this->assertSame('a\\b', $name->value);
    }

    public function testParseParametersWithExtendedContinuations(): void
    {
        $params = Parameter::parseParameters([
            "filename*0*=utf-8'en'%E2%82%AC%20",
            "filename*1*=rates.txt",
        ]);

        $filename = $this->getParamByName($params, 'filename');

        $this->assertSame('€ rates.txt', $filename->value);
        $this->assertSame('utf-8', $filename->charset);
        $this->assertSame('en', $filename->language);
    }

    /**
     * @param Parameter[] $params
     */
    private function getParamByName(array $params, string $name): Parameter
    {
        foreach ($params as $param) {
            if ($param->lowerName() === strtolower($name)) {
                return $param;
            }
        }

        $this->fail("Parameter {$name} not found.");
    }
}

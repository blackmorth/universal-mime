<?php

declare(strict_types=1);

namespace UniversalMime\Tests\Model;

use PHPUnit\Framework\TestCase;
use UniversalMime\Model\HeaderValueTokenizer;

final class HeaderValueTokenizerTest extends TestCase
{
    public function testSplitMainAndParametersWithQuotedSemicolon(): void
    {
        [$main, $params] = HeaderValueTokenizer::splitMainAndParameters(
            'attachment; filename="report;v1.txt"; charset=utf-8'
        );

        $this->assertSame('attachment', $main);
        $this->assertSame(['filename="report;v1.txt"', 'charset=utf-8'], $params);
    }
}

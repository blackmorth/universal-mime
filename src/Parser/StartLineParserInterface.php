<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Model\StartLine;

interface StartLineParserInterface
{
    public function parse(string $line): StartLine;
}

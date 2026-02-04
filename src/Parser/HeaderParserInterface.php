<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Model\HeaderBag;
use UniversalMime\Wire\Header\RawHeaderBlock;

interface HeaderParserInterface
{
    /**
     * Parse raw unfolded/folded header lines into a structured HeaderBag.
     */
    public function parse(RawHeaderBlock $block): HeaderBag;
}

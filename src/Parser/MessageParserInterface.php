<?php

declare(strict_types=1);

namespace UniversalMime\Parser;

use UniversalMime\Model\Message;
use UniversalMime\Parser\Stream\StreamInterface;

interface MessageParserInterface
{
    /**
     * Parse a complete message from a stream.
     * (Start-Line + Headers + Body)
     */
    public function parse(StreamInterface $stream): Message;
}

<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

final class MultipartStream implements \IteratorAggregate
{
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator([]);
    }
}

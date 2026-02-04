<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['5322', 'Internet Message Format (Multiple Header Fields)'],
])]
final class HeaderBag implements \IteratorAggregate, \Countable
{
    /** @var Header[] */
    private readonly array $headers;

    /** @var array<string, Header[]> lowercase index */
    private readonly array $index;

    /**
     * @param Header[] $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;

        $idx = [];
        foreach ($headers as $h) {
            $key = strtolower($h->name);
            $idx[$key][] = $h;
        }
        $this->index = $idx;
    }

    public function get(string $name): ?Header
    {
        $key = strtolower($name);
        return $this->index[$key][0] ?? null;
    }

    /**
     * @return Header[]
     */
    public function all(string $name): array
    {
        return $this->index[strtolower($name)] ?? [];
    }

    /**
     * @return Header[]
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function count(): int
    {
        return count($this->headers);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->headers);
    }
}

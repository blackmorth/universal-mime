<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

final class LengthLimitedStream implements StreamInterface
{
    public function read(int $length): ?string
    {
        return '';
    }

    public function readLine(): ?string
    {
        return null;
    }

    public function eof(): bool
    {
        return true;
    }

    public function close(): void
    {
    }

    public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }

    public function write(string $data): void
    {
        // TODO: Implement write() method.
    }

    public function unshift(string $data): void
    {
        // TODO: Implement unshift() method.
    }
}

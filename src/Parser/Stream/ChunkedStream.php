<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

final class ChunkedStream implements StreamInterface
{
    public function __construct(private readonly StreamInterface $stream)
    {
    }

    public function read(int $length): ?string
    {
        return $this->stream->read($length);
    }

    public function readLine(): ?string
    {
        return $this->stream->readLine();
    }

    public function eof(): bool
    {
        return $this->stream->eof();
    }

    public function close(): void
    {
        $this->stream->close();
    }

    public function rewind(): void
    {
        $this->stream->rewind();
    }

    public function write(string $data): void
    {
        $this->stream->write($data);
    }

    public function unshift(string $data): void
    {
        $this->stream->unshift($data);
    }

    public function unshift(string $data): void
    {
        // TODO: Implement unshift() method.
    }
}

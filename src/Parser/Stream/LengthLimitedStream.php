<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

final class LengthLimitedStream implements StreamInterface
{
    private int $consumed = 0;

    public function __construct(
        private readonly StreamInterface $stream,
        private readonly int $limit
    ) {
    }

    public function read(int $length): ?string
    {
        if ($this->eof()) {
            return null;
        }

        $remaining = $this->limit - $this->consumed;
        $chunk = $this->stream->read(min($length, $remaining));
        if ($chunk === null) {
            return null;
        }

        $this->consumed += strlen($chunk);
        return $chunk;
    }

    public function readLine(): ?string
    {
        if ($this->eof()) {
            return null;
        }

        $remaining = $this->limit - $this->consumed;
        $line = $this->stream->readLine();
        if ($line === null) {
            return null;
        }

        if (strlen($line) > $remaining) {
            $head = substr($line, 0, $remaining);
            $tail = substr($line, $remaining);
            if ($tail !== '') {
                $this->stream->unshift($tail);
            }
            $line = $head;
        }

        $this->consumed += strlen($line);
        return $line;
    }

    public function readLine(): ?string
    {
        return null;
    }

    public function eof(): bool
    {
        return $this->consumed >= $this->limit || $this->stream->eof();
    }

    public function close(): void
    {
        $this->stream->close();
    }

    public function rewind(): void
    {
        $this->stream->rewind();
        $this->consumed = 0;
    }

    public function write(string $data): void
    {
        $this->stream->write($data);
    }

    public function unshift(string $data): void
    {
        $this->stream->unshift($data);
        $this->consumed -= strlen($data);
        if ($this->consumed < 0) {
            $this->consumed = 0;
        }
    }

    public function unshift(string $data): void
    {
        // TODO: Implement unshift() method.
    }
}

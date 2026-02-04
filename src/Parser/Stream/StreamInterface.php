<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

interface StreamInterface
{
    public function read(int $length): ?string;
    public function readLine(): ?string;
    public function eof(): bool;
    public function rewind(): void;
    public function close(): void;
    public function write(string $data): void;
    public function unshift(string $data): void;
}

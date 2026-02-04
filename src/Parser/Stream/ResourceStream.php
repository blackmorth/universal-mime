<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

final class ResourceStream implements StreamInterface
{
    /**
     * @param resource $handle
     */
    public function __construct(
        private $handle
    ) {
    }

    public function readLine(): ?string
    {
        if (feof($this->handle)) {
            return null;
        }
        return fgets($this->handle);
    }

    public function read(int $length): ?string
    {
        if (feof($this->handle)) {
            return null;
        }
        return fread($this->handle, $length);
    }

    public function rewind(): void
    {
        rewind($this->handle);
    }

    public function eof(): bool
    {
        return feof($this->handle);
    }

    /**
     * Permet d’écrire dans le flux (pratique pour les tests).
     */
    public function write(string $data): void
    {
        fwrite($this->handle, $data);
    }

    public function close(): void
    {
        fclose($this->handle);
    }
}

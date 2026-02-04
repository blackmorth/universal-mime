<?php

declare(strict_types=1);

namespace UniversalMime\Parser\Stream;

final class ResourceStream implements StreamInterface
{
    private string $pushback = '';

    /**
     * @param resource $handle
     */
    public function __construct(
        private $handle
    ) {
    }

    public function readLine(): ?string
    {
        if ($this->pushback !== '') {
            $pos = strpos($this->pushback, "\n");
            if ($pos !== false) {
                $line = substr($this->pushback, 0, $pos + 1);
                $this->pushback = substr($this->pushback, $pos + 1);
                return $line;
            }

            $buffer = $this->pushback;
            $this->pushback = '';

            if (feof($this->handle)) {
                return $buffer;
            }

            $tail = fgets($this->handle);
            if ($tail === false) {
                return $buffer;
            }

            return $buffer . $tail;
        }

        if (feof($this->handle)) {
            return null;
        }
        return fgets($this->handle);
    }

    public function read(int $length): ?string
    {
        if ($this->pushback !== '') {
            $chunk = substr($this->pushback, 0, $length);
            $this->pushback = substr($this->pushback, strlen($chunk));

            $remaining = $length - strlen($chunk);
            if ($remaining <= 0) {
                return $chunk;
            }

            if (feof($this->handle)) {
                return $chunk === '' ? null : $chunk;
            }

            $tail = fread($this->handle, $remaining);
            if ($tail === false) {
                return $chunk === '' ? null : $chunk;
            }

            return $chunk . $tail;
        }

        if (feof($this->handle)) {
            return null;
        }
        return fread($this->handle, $length);
    }

    public function rewind(): void
    {
        rewind($this->handle);
        $this->pushback = '';
    }

    public function eof(): bool
    {
        return $this->pushback === '' && feof($this->handle);
    }

    /**
     * Permet d’écrire dans le flux (pratique pour les tests).
     */
    public function write(string $data): void
    {
        fwrite($this->handle, $data);
    }

    public function unshift(string $data): void
    {
        $this->pushback = $data . $this->pushback;
    }

    public function close(): void
    {
        fclose($this->handle);
    }
}

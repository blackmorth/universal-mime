<?php

declare(strict_types=1);

namespace UniversalMime\Encoding\Transfer;

use UniversalMime\Attributes\TransferEncoding;
use UniversalMime\Parser\Stream\StreamInterface;

final class TransferEncodingRegistry implements \UniversalMime\Encoding\Transfer\TransferDecoderProvider
{
    /** @var array<string, TransferDecoderInterface> */
    private array $decoders = [];

    /**
     * @param string[] $classList Liste des classes à analyser
     */
    public function __construct(array $classList)
    {
        foreach ($classList as $class) {
            $this->registerClass($class);
        }
    }

    private function registerClass(string $class): void
    {
        $ref = new \ReflectionClass($class);

        foreach ($ref->getAttributes(TransferEncoding::class) as $attr) {
            /** @var TransferEncoding $annotation */
            $annotation = $attr->newInstance();

            $encoding = strtolower($annotation->encoding);

            /** @var TransferDecoderInterface $instance */
            $instance = $ref->newInstance();

            $this->decoders[$encoding] = $instance;
        }
    }

    public function has(string $encoding): bool
    {
        return isset($this->decoders[strtolower($encoding)]);
    }

    public function get(string $encoding): TransferDecoderInterface
    {
        $encoding = strtolower($encoding);

        if (isset($this->decoders[$encoding])) {
            return $this->decoders[$encoding];
        }

        // Fallback RFC 2045: 7bit is identity
        if (isset($this->decoders['7bit'])) {
            return $this->decoders['7bit'];
        }

        throw new \RuntimeException("No Transfer-Encoding decoder found for: $encoding");
    }
}

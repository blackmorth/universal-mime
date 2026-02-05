<?php

declare(strict_types=1);

namespace UniversalMime\Model;

use UniversalMime\Attributes\RFC;

#[RFC([
    ['2231', 'MIME Parameter Value and Parameter Value Continuations'],
])]
final class Parameter
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
        public readonly ?string $charset = null,
        public readonly ?string $language = null,
    ) {
    }

    public function lowerName(): string
    {
        return strtolower($this->name);
    }

    public function hasExtended(): bool
    {
        return $this->charset !== null || $this->language !== null;
    }

    /**
     * @param string[] $parameterStrings
     * @return Parameter[]
     */
    public static function parseParameters(array $parameterStrings): array
    {
        $parameters = [];
        $continuations = [];

        foreach ($parameterStrings as $parameterString) {
            if ($parameterString === '') {
                continue;
            }

            $eqPos = strpos($parameterString, '=');
            if ($eqPos === false) {
                continue;
            }

            $name = trim(substr($parameterString, 0, $eqPos));
            $value = trim(substr($parameterString, $eqPos + 1));
            $value = self::unquote($value);

            if (preg_match('/^(.+)\\*(\\d+)\\*?$/', $name, $matches)) {
                $base = $matches[1];
                $index = (int) $matches[2];
                $extended = str_ends_with($name, '*');
                $continuations[$base][$index] = [
                    'value' => $value,
                    'extended' => $extended,
                ];
                continue;
            }

            if (str_ends_with($name, '*')) {
                $base = substr($name, 0, -1);
                [$decoded, $charset, $language] = self::decodeExtendedValue($value);
                $parameters[$base] = new self($base, $decoded, $charset, $language);
                continue;
            }

            $parameters[$name] = new self($name, $value);
        }

        foreach ($continuations as $base => $segments) {
            ksort($segments);
            $buffer = '';
            $charset = null;
            $language = null;
            $extended = false;

            foreach ($segments as $index => $segment) {
                $segmentValue = $segment['value'];
                if ($index === 0 && $segment['extended']) {
                    [$decoded, $charset, $language] = self::decodeExtendedValue($segmentValue);
                    $buffer .= $decoded;
                    $extended = true;
                    continue;
                }

                if ($segment['extended']) {
                    $buffer .= self::percentDecode($segmentValue);
                    $extended = true;
                } else {
                    $buffer .= $segmentValue;
                }
            }

            $parameters[$base] = new self(
                $base,
                $buffer,
                $extended ? $charset : null,
                $extended ? $language : null
            );
        }

        return array_values($parameters);
    }

    private static function unquote(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return $value;
        }

        if ($value[0] === '"' && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
            $value = str_replace(['\\"', '\\\\'], ['"', '\\'], $value);
        }

        return $value;
    }

    /**
     * @return array{0:string,1:?string,2:?string}
     */
    private static function decodeExtendedValue(string $value): array
    {
        $parts = explode("'", $value, 3);
        if (count($parts) === 3) {
            [$charset, $language, $encoded] = $parts;
            return [self::percentDecode($encoded), $charset ?: null, $language ?: null];
        }

        return [self::percentDecode($value), null, null];
    }

    private static function percentDecode(string $value): string
    {
        return rawurldecode($value);
    }
}

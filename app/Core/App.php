<?php

namespace App\Core;

class App
{
    private static array $container = [];
    private static array $config = [];

    public static function set(string $key, mixed $value): void
    {
        self::$container[$key] = $value;
    }

    public static function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return self::$container;
        }

        return self::$container[$key] ?? $default;
    }

    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}

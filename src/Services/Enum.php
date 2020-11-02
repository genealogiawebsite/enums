<?php

namespace LaravelEnso\Enums\Services;

use Illuminate\Support\Collection;
use LaravelEnso\Enums\Exceptions\Enum as Exception;
use ReflectionClass;

class Enum
{
    protected static array $data;
    protected static bool $localisation = true;
    protected static bool $validatesKeys = false;

    public static function constants(): array
    {
        return array_flip(
            (new ReflectionClass(static::class))
                ->getConstants()
        );
    }

    public static function get($key)
    {
        if (static::$validatesKeys && ! self::has($key)) {
            throw Exception::keyNotFound();
        }

        return self::attributes()->get($key);
    }

    public static function has($key): bool
    {
        return self::attributes()->has($key);
    }

    public static function keys(): Collection
    {
        return self::attributes()->keys();
    }

    public static function values(): Collection
    {
        return self::attributes()->values();
    }

    public static function json(): string
    {
        return self::attributes()->toJson();
    }

    public static function array(): array
    {
        return self::attributes()->toArray();
    }

    public static function all(): array
    {
        return self::array();
    }

    public static function object(): object
    {
        return (object) self::array();
    }

    public static function collection(): Collection
    {
        return self::attributes();
    }

    public static function select(): Collection
    {
        return self::attributes()
            ->map(fn ($value, $key) => (object) ['id' => $key, 'name' => $value])
            ->values();
    }

    public static function localisation(bool $state = true, bool $global = true): void
    {
        if ($global) {
            self::$localisation = $state;
        } else {
            static::$localisation = $state;
        }
    }

    protected static function data(): array
    {
        return [];
    }

    private static function attributes(): Collection
    {
        return self::transAll(self::source());
    }

    private static function source(): array
    {
        if (isset(static::$data)) {
            return static::$data;
        }

        if (! empty(static::data())) {
            return static::data();
        }

        return static::constants();
    }

    private static function transAll($data): Collection
    {
        return (new Collection($data))->map(fn ($value) => self::trans($value));
    }

    private static function trans($value)
    {
        return is_string($value) && static::$localisation
            ? __($value)
            : $value;
    }
}

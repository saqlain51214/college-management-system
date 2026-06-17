<?php

namespace App\Helpers;

class ValidationHelper
{
    private static array $cache = [];

    // ─── Load JSON ───────────────────────────────────────────────────────────

    private static function load(): array
    {
        if (empty(self::$cache)) {
            $path = resource_path('json/validation-messages.json');
            self::$cache = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
        }
        return self::$cache;
    }

    public static function message(string $key): string
    {
        return self::load()['messages'][$key] ?? '';
    }

    public static function pattern(string $key): string
    {
        return self::load()['regex'][$key] ?? '';
    }

    // ─── HTML5 extraAttributes builder ───────────────────────────────────────
    // Pass to Filament ->extraAttributes([...]) for browser-native validation

    public static function nameAttrs(bool $required = true): array
    {
        return array_filter([
            'required'  => $required ?: null,
            'minlength' => '3',
            'maxlength' => '100',
            'pattern'   => '[A-Za-z .\\-]+',
            'title'     => self::message('regex_name'),
        ]);
    }

    public static function codeAttrs(bool $required = false): array
    {
        return array_filter([
            'required'  => $required ?: null,
            'minlength' => '2',
            'maxlength' => '20',
            'pattern'   => '[A-Z0-9\\-]+',
            'title'     => self::message('regex_code'),
        ]);
    }

    public static function slugAttrs(): array
    {
        return [
            'maxlength' => '150',
            'pattern'   => '[a-z0-9\\-]+',
            'title'     => self::message('regex_slug'),
        ];
    }

    public static function phoneAttrs(bool $required = false): array
    {
        return array_filter([
            'required'  => $required ?: null,
            'minlength' => '10',
            'maxlength' => '15',
            'pattern'   => '(\\+92|0)[0-9]{9,10}',
            'title'     => self::message('regex_phone'),
        ]);
    }

    public static function emailAttrs(bool $required = false): array
    {
        return array_filter([
            'required'  => $required ?: null,
            'maxlength' => '150',
        ]);
    }

    public static function textAttrs(int $min = 0, int $max = 255, bool $required = false): array
    {
        return array_filter([
            'required'  => $required ?: null,
            'minlength' => $min > 0 ? (string) $min : null,
            'maxlength' => (string) $max,
        ]);
    }

    public static function numberAttrs(int $min = 0, int $max = 999, bool $required = false): array
    {
        return array_filter([
            'required' => $required ?: null,
            'min'      => (string) $min,
            'max'      => (string) $max,
        ]);
    }

    public static function cnicAttrs(bool $required = false): array
    {
        return array_filter([
            'required'  => $required ?: null,
            'minlength' => '15',
            'maxlength' => '15',
            'pattern'   => '[0-9]{5}-[0-9]{7}-[0-9]{1}',
            'title'     => self::message('regex_cnic'),
        ]);
    }
}

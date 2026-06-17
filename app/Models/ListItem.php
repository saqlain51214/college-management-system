<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $fillable = ['category', 'value', 'label', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    protected static array $optionsCache = [];

    public static function getOptions(string $category, array $fallback = []): array
    {
        if (! isset(static::$optionsCache[$category])) {
            static::$optionsCache[$category] = static::where('category', $category)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('label')
                ->pluck('label', 'value')
                ->all();
        }
        return static::$optionsCache[$category] ?: $fallback;
    }

    public static function getLabel(string $category, ?string $value, string $default = ''): string
    {
        if ($value === null) return $default;
        return static::getOptions($category)[$value] ?? $value;
    }

    public static function forCategory(string $category)
    {
        return static::where('category', $category)->orderBy('sort_order')->orderBy('label');
    }

    public static function clearCache(string $category = null): void
    {
        if ($category) {
            unset(static::$optionsCache[$category]);
        } else {
            static::$optionsCache = [];
        }
    }
}

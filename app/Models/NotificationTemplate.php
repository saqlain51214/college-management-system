<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'key', 'name', 'description', 'channel',
        'subject', 'body', 'action_label', 'action_url',
        'in_app_icon', 'variables', 'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->where('is_active', true)->first();
    }

    public function replaceVariables(string $text, array $variables): string
    {
        foreach ($variables as $k => $v) {
            $text = str_replace('{{' . $k . '}}', (string) $v, $text);
        }

        return $text;
    }
}

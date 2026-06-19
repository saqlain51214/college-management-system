<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ActivityLogWriter
{
    private static ?bool $tableExists = null;

    public static function activity(string $event, ?string $message = null, array $meta = [], mixed $subject = null, mixed $actor = null): ?ActivityLog
    {
        return static::write(ActivityLog::TYPE_ACTIVITY, ActivityLog::LEVEL_INFO, $event, $message, $meta, $subject, $actor);
    }

    public static function auth(string $event, ?string $message = null, array $meta = [], mixed $subject = null, mixed $actor = null): ?ActivityLog
    {
        return static::write(ActivityLog::TYPE_AUTH, ActivityLog::LEVEL_INFO, $event, $message, $meta, $subject, $actor);
    }

    public static function warning(string $event, ?string $message = null, array $meta = [], mixed $subject = null, mixed $actor = null): ?ActivityLog
    {
        return static::write(ActivityLog::TYPE_SYSTEM, ActivityLog::LEVEL_WARNING, $event, $message, $meta, $subject, $actor);
    }

    public static function error(string $event, ?string $message = null, array $meta = [], mixed $subject = null, mixed $actor = null): ?ActivityLog
    {
        return static::write(ActivityLog::TYPE_ERROR, ActivityLog::LEVEL_ERROR, $event, $message, $meta, $subject, $actor);
    }

    public static function write(
        int $type,
        int $level,
        string $event,
        ?string $message = null,
        array $meta = [],
        mixed $subject = null,
        mixed $actor = null
    ): ?ActivityLog {
        if (! static::activityTableExists()) {
            return null;
        }

        try {
            $actorModel = static::resolveActor($actor);
            $subjectRef = static::resolveModelReference($subject);
            $actorRef = static::resolveModelReference($actorModel);

            return ActivityLog::query()->create([
                'type' => $type,
                'level' => $level,
                'event' => Str::limit($event, 80, ''),
                'actor_type' => $actorRef['type'],
                'actor_id' => $actorRef['id'],
                'subject_type' => $subjectRef['type'],
                'subject_id' => $subjectRef['id'],
                'message' => filled($message) ? Str::limit(trim($message), 255, '') : null,
                'meta' => static::prepareMeta($meta),
                'created_at' => now(),
            ]);
        } catch (Throwable) {
            return null;
        }
    }

    private static function activityTableExists(): bool
    {
        if (static::$tableExists !== null) {
            return static::$tableExists;
        }

        try {
            static::$tableExists = Schema::hasTable('activity_logs');
        } catch (Throwable) {
            static::$tableExists = false;
        }

        return static::$tableExists;
    }

    private static function resolveActor(mixed $actor = null): mixed
    {
        if ($actor instanceof Model) {
            return $actor;
        }

        foreach (['web', 'student', 'teacher'] as $guard) {
            try {
                if (Auth::guard($guard)->check()) {
                    return Auth::guard($guard)->user();
                }
            } catch (Throwable) {
                // Ignore guard resolution failures and continue.
            }
        }

        return null;
    }

    private static function resolveModelReference(mixed $model): array
    {
        if (! $model instanceof Model) {
            return ['type' => null, 'id' => null];
        }

        return [
            'type' => static::modelAlias($model::class),
            'id' => $model->getKey(),
        ];
    }

    private static function modelAlias(string $class): string
    {
        return Str::snake(class_basename($class));
    }

    private static function prepareMeta(array $meta): ?array
    {
        $prepared = static::sanitizeValue($meta);

        return is_array($prepared) && $prepared !== [] ? $prepared : null;
    }

    private static function sanitizeValue(mixed $value, int $depth = 0): mixed
    {
        if ($depth > 3) {
            return null;
        }

        if ($value instanceof Model) {
            return [
                'type' => static::modelAlias($value::class),
                'id' => $value->getKey(),
            ];
        }

        if (is_scalar($value)) {
            return is_string($value) ? Str::limit($value, 255, '') : $value;
        }

        if ($value instanceof Throwable) {
            return [
                'class' => class_basename($value),
                'message' => Str::limit($value->getMessage(), 255, ''),
            ];
        }

        if (! is_array($value)) {
            return null;
        }

        $sanitized = [];

        foreach (array_slice($value, 0, 20, true) as $key => $item) {
            $cleanValue = static::sanitizeValue($item, $depth + 1);

            if ($cleanValue === null || $cleanValue === []) {
                continue;
            }

            $sanitized[(string) $key] = $cleanValue;
        }

        return $sanitized;
    }
}

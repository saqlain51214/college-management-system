<?php

namespace App\Observers;

use App\Support\ActivityLogWriter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        ActivityLogWriter::activity(
            'created',
            'Created ' . $this->modelLabel($model),
            $this->baseMeta($model),
            $model
        );
    }

    public function updated(Model $model): void
    {
        $changed = collect(array_keys($model->getChanges()))
            ->reject(fn (string $attribute) => in_array($attribute, [
                'updated_at',
                'created_at',
                'deleted_at',
                'password',
                'portal_password',
                'remember_token',
            ], true))
            ->values()
            ->take(20)
            ->all();

        if ($changed === []) {
            return;
        }

        ActivityLogWriter::activity(
            'updated',
            'Updated ' . $this->modelLabel($model),
            array_merge($this->baseMeta($model), ['changed' => $changed]),
            $model
        );
    }

    public function deleted(Model $model): void
    {
        ActivityLogWriter::warning(
            'deleted',
            'Deleted ' . $this->modelLabel($model),
            array_merge($this->baseMeta($model), ['soft_delete' => $this->usesSoftDeletes($model)]),
            $model
        );
    }

    public function restored(Model $model): void
    {
        ActivityLogWriter::activity(
            'restored',
            'Restored ' . $this->modelLabel($model),
            $this->baseMeta($model),
            $model
        );
    }

    public function forceDeleted(Model $model): void
    {
        ActivityLogWriter::warning(
            'force_deleted',
            'Permanently deleted ' . $this->modelLabel($model),
            $this->baseMeta($model),
            $model
        );
    }

    private function baseMeta(Model $model): array
    {
        $meta = [
            'label' => $this->modelIdentity($model),
        ];

        return array_filter($meta, fn ($value) => filled($value));
    }

    private function modelLabel(Model $model): string
    {
        return Str::headline(class_basename($model));
    }

    private function modelIdentity(Model $model): ?string
    {
        foreach (['title', 'name', 'slug', 'roll_number', 'employee_id', 'key', 'code'] as $attribute) {
            $value = $model->getAttribute($attribute);

            if (filled($value)) {
                return Str::limit((string) $value, 120, '');
            }
        }

        return null;
    }

    private function usesSoftDeletes(Model $model): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model), true);
    }
}

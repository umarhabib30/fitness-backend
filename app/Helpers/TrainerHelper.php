<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TrainerHelper
{
    public static function isTrainer(): bool
    {
        return Auth::check() && Auth::user()->hasRole('trainer');
    }

    public static function trainer()
    {
        if (!self::isTrainer()) {
            return null;
        }

        return Auth::user()->trainerProfile;
    }

    public static function trainerId(): ?int
    {
        return self::trainer()?->id;
    }

    public static function applyScope(Builder $query, string $column = 'trainer_id'): Builder
    {
        if (self::isTrainer()) {
            $table = $query->getModel()->getTable();

            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                return $query;
            }

            if (!self::trainerId()) {
                $query->whereRaw('1 = 0');
                return $query;
            }

            $query->where($column, self::trainerId());
        }

        return $query;
    }

    public static function setTrainerId(array $data, string $column = 'trainer_id'): array
    {
        if (!self::isTrainer() || !self::trainerId()) {
            return $data;
        }

        $modelClass = static::detectModelClassFromTrace();

        if ($modelClass) {
            $model = new $modelClass();
            if (!Schema::hasTable($model->getTable()) || !Schema::hasColumn($model->getTable(), $column)) {
                return $data;
            }
        }

        if (self::isTrainer() && self::trainerId()) {
            $data[$column] = self::trainerId();
        }

        return $data;
    }

    public static function owned(Model $model, string $column = 'trainer_id'): bool
    {
        if (!self::isTrainer()) {
            return true;
        }

        return (int) $model->getAttribute($column) === (int) self::trainerId();
    }

    public static function abortIfUnauthorized(Model $model, string $column = 'trainer_id'): void
    {
        abort_unless(self::owned($model, $column), 404);
    }

    protected static function detectModelClassFromTrace(): ?string
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
            if (!isset($trace['class'])) {
                continue;
            }

            if (is_subclass_of($trace['class'], Model::class)) {
                return $trace['class'];
            }
        }

        return null;
    }
}

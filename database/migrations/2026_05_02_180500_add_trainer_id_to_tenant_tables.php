<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // List of tenant tables that should be scoped to a trainer
        $tables = [
            'equipment',
            'body_parts',
            'exercises',
            'diets',
            'workouts',
            'class_schedules',
            'packages',
            'subscriptions',
            'recipes',
            'daily_plans',
            'daily_plan_recipes',
            'recipe_ingredients',
            'recipe_steps',
            'recipe_tags',
            'assign_diets',
            'assign_workouts',
            // add any other tables that can be owned by a trainer
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (!Schema::hasColumn($table, 'trainer_id')) {
                    $blueprint->unsignedBigInteger('trainer_id')->nullable()->after('id');
                }
            });

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $foreignKeys = collect(Schema::getForeignKeys($table))
                    ->pluck('columns')
                    ->flatten()
                    ->all();

                if (!in_array('trainer_id', $foreignKeys, true)) {
                    $blueprint->foreign('trainer_id')->references('id')->on('trainers')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'equipment',
            'body_parts',
            'exercises',
            'diets',
            'workouts',
            'class_schedules',
            'packages',
            'subscriptions',
            'recipes',
            'daily_plans',
            'daily_plan_recipes',
            'recipe_ingredients',
            'recipe_steps',
            'recipe_tags',
            'assign_diets',
            'assign_workouts',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'trainer_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $foreignKeys = collect(Schema::getForeignKeys($table))
                    ->pluck('columns')
                    ->flatten()
                    ->all();

                if (in_array('trainer_id', $foreignKeys, true)) {
                    $blueprint->dropForeign(['trainer_id']);
                }

                $blueprint->dropColumn('trainer_id');
            });
        }
    }
};

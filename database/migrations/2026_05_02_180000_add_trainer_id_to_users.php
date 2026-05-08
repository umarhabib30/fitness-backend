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
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'trainer_id')) {
                $table->unsignedBigInteger('trainer_id')->nullable()->after('id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $foreignKeys = collect(Schema::getForeignKeys('users'))
                ->pluck('columns')
                ->flatten()
                ->all();

            if (!in_array('trainer_id', $foreignKeys, true)) {
                $table->foreign('trainer_id')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'trainer_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $foreignKeys = collect(Schema::getForeignKeys('users'))
                ->pluck('columns')
                ->flatten()
                ->all();

            if (in_array('trainer_id', $foreignKeys, true)) {
                $table->dropForeign(['trainer_id']);
            }

            $table->dropColumn('trainer_id');
        });
    }
};

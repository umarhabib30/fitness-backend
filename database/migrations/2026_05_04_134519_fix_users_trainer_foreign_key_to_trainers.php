<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'trainer_id') && Schema::hasColumn('trainers', 'user_id')) {
            $mapping = DB::table('trainers')->whereNotNull('user_id')->pluck('id', 'user_id');

            DB::table('users')
                ->whereNotNull('trainer_id')
                ->orderBy('id')
                ->get(['id', 'trainer_id'])
                ->each(function ($user) use ($mapping) {
                    if (isset($mapping[$user->trainer_id])) {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['trainer_id' => $mapping[$user->trainer_id]]);
                    } else {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['trainer_id' => null]);
                    }
                });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['trainer_id']);
            $table->foreign('trainer_id')->references('id')->on('trainers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['trainer_id']);
            $table->foreign('trainer_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};

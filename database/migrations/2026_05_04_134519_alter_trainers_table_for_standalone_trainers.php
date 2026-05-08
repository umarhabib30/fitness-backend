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
        Schema::table('trainers', function (Blueprint $table) {
            if (!Schema::hasColumn('trainers', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('trainers', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('trainers', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }
            if (!Schema::hasColumn('trainers', 'password')) {
                $table->string('password')->nullable()->after('phone_number');
            }
        });

        if (Schema::hasColumn('trainers', 'user_id')) {
            DB::table('trainers')
                ->join('users', 'users.id', '=', 'trainers.user_id')
                ->update([
                    'trainers.name' => DB::raw("COALESCE(NULLIF(CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, '')), ' '), users.display_name, users.email)"),
                    'trainers.email' => DB::raw('COALESCE(trainers.email, users.email)'),
                    'trainers.phone_number' => DB::raw('COALESCE(trainers.phone_number, users.phone_number)'),
                    'trainers.password' => DB::raw('COALESCE(trainers.password, users.password)'),
                ]);
        }

        Schema::table('trainers', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            if (Schema::hasColumn('trainers', 'email')) {
                $table->dropUnique(['email']);
            }
            if (Schema::hasColumn('trainers', 'password')) {
                $table->dropColumn('password');
            }
            if (Schema::hasColumn('trainers', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('trainers', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('trainers', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};

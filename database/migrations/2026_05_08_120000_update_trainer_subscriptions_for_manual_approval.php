<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainer_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('trainer_subscriptions', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('status');
            }
        });

        DB::statement("ALTER TABLE trainer_subscriptions MODIFY status ENUM('pending', 'active', 'inactive', 'expired', 'canceled', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE trainer_subscriptions MODIFY status ENUM('active', 'inactive', 'expired', 'canceled') NOT NULL DEFAULT 'active'");

        Schema::table('trainer_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('trainer_subscriptions', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
        });
    }
};

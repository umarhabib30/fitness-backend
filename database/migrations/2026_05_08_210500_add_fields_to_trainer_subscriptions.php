<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainer_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('trainer_subscriptions', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('trainer_subscriptions', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainer_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('trainer_subscriptions', 'transaction_reference')) {
                $table->dropColumn('transaction_reference');
            }
            if (Schema::hasColumn('trainer_subscriptions', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('loyalty_reward_coupons')) {
            return;
        }

        Schema::table('loyalty_reward_coupons', function (Blueprint $table) {
            if (! Schema::hasColumn('loyalty_reward_coupons', 'show_on_mobile_home')) {
                $table->boolean('show_on_mobile_home')->default(true)->after('branch_ids');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('loyalty_reward_coupons')) {
            return;
        }

        Schema::table('loyalty_reward_coupons', function (Blueprint $table) {
            if (Schema::hasColumn('loyalty_reward_coupons', 'show_on_mobile_home')) {
                $table->dropColumn('show_on_mobile_home');
            }
        });
    }
};

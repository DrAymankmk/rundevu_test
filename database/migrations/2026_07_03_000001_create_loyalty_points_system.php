<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyPointsSystem extends Migration
{
    public function up()
    {
        $this->createLoyaltyPointRulesTable();
        $this->createLoyaltyPointTransactionsTable();
        $this->createLoyaltyRewardCouponsTable();
        $this->createLoyaltyCouponRedemptionsTable();
        $this->createLoyaltyShareLogsTable();
        $this->addClinicsColumns();
        $this->seedLoyaltyPointRules();
        $this->purgeOrphanedUserReferences();
        $this->addForeignKeys();
    }

    public function down()
    {
        $this->dropForeignKeys();

        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'points_category')) {
                $table->dropColumn('points_category');
            }
            if (Schema::hasColumn('clinics', 'points_enabled')) {
                $table->dropColumn('points_enabled');
            }
        });

        Schema::dropIfExists('loyalty_share_logs');
        Schema::dropIfExists('loyalty_coupon_redemptions');
        Schema::dropIfExists('loyalty_reward_coupons');
        Schema::dropIfExists('loyalty_point_transactions');
        Schema::dropIfExists('loyalty_point_rules');
    }

    private function createLoyaltyPointRulesTable(): void
    {
        if (Schema::hasTable('loyalty_point_rules')) {
            return;
        }

        Schema::create('loyalty_point_rules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name_ar');
            $table->string('name_en');
            $table->integer('points')->default(0);
            $table->unsignedInteger('max_per_day')->nullable();
            $table->unsignedInteger('min_words')->nullable();
            $table->unsignedInteger('expires_after_months')->default(12);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    private function createLoyaltyPointTransactionsTable(): void
    {
        if (Schema::hasTable('loyalty_point_transactions')) {
            return;
        }

        Schema::create('loyalty_point_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('rule_key')->nullable();
            $table->enum('type', ['earn', 'spend', 'reversal', 'expire', 'adjustment'])->default('earn');
            $table->integer('points');
            $table->string('description_ar')->nullable();
            $table->string('description_en')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index(['user_id', 'status', 'expires_at']);
            $table->index(['source_type', 'source_id']);
        });
    }

    private function createLoyaltyRewardCouponsTable(): void
    {
        if (Schema::hasTable('loyalty_reward_coupons')) {
            return;
        }

        Schema::create('loyalty_reward_coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->string('service_name_ar');
            $table->string('service_name_en')->nullable();
            $table->text('details_ar')->nullable();
            $table->text('details_en')->nullable();
            // price before discount
            $table->decimal('price_before_discount', 10, 2)->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            // price after discount
            $table->decimal('price_after_discount', 10, 2)->default(0);
            // usage limit
            $table->unsignedInteger('usage_limit')->default(0);
            $table->date('expires_at');
            $table->json('branch_ids')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'status', 'expires_at']);
        });
    }

    private function createLoyaltyCouponRedemptionsTable(): void
    {
        if (Schema::hasTable('loyalty_coupon_redemptions')) {
            return;
        }

        Schema::create('loyalty_coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('clinic_id');
            $table->string('code', 40)->unique();
            $table->string('otp_code', 10)->nullable();
            $table->unsignedInteger('points_spent');
            $table->enum('status', ['pending', 'otp_sent', 'used', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'status']);
        });
    }

    private function createLoyaltyShareLogsTable(): void
    {
        if (Schema::hasTable('loyalty_share_logs')) {
            return;
        }

        Schema::create('loyalty_share_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->string('shareable_type')->nullable();
            $table->unsignedBigInteger('shareable_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    private function addClinicsColumns(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'points_enabled')) {
                $table->boolean('points_enabled')->default(false)->after('status');
            }
            if (!Schema::hasColumn('clinics', 'points_category')) {
                $table->string('points_category')->nullable()->after('points_enabled');
            }
        });
    }

    private function seedLoyaltyPointRules(): void
    {
        if (!Schema::hasTable('loyalty_point_rules') || DB::table('loyalty_point_rules')->exists()) {
            return;
        }

        DB::table('loyalty_point_rules')->insert([
            [
                'key' => 'welcome',
                'name_ar' => 'هدية الترحيب',
                'name_en' => 'Welcome gift',
                'points' => 50,
                'max_per_day' => null,
                'min_words' => null,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'completed_visit',
                'name_ar' => 'إتمام الكشف',
                'name_en' => 'Completed visit',
                'points' => 20,
                'max_per_day' => null,
                'min_words' => null,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'clinic_cancel_compensation',
                'name_ar' => 'تعويض إلغاء العيادة',
                'name_en' => 'Clinic cancellation compensation',
                'points' => 5,
                'max_per_day' => null,
                'min_words' => null,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'share',
                'name_ar' => 'مشاركة من التطبيق',
                'name_en' => 'In-app share',
                'points' => 5,
                'max_per_day' => 2,
                'min_words' => null,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'referral',
                'name_ar' => 'مكافأة دعوة مستخدم',
                'name_en' => 'Referral bonus',
                'points' => 20,
                'max_per_day' => null,
                'min_words' => null,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'rating',
                'name_ar' => 'تقييم بعد الزيارة',
                'name_en' => 'Visit rating',
                'points' => 10,
                'max_per_day' => null,
                'min_words' => 20,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'electronic_payment',
                'name_ar' => 'الدفع الإلكتروني',
                'name_en' => 'Electronic payment',
                'points' => 10,
                'max_per_day' => null,
                'min_words' => null,
                'expires_after_months' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function purgeOrphanedUserReferences(): void
    {
        if (Schema::hasTable('loyalty_point_transactions')) {
            DB::table('loyalty_point_transactions as t')
                ->leftJoin('users as u', 'u.id', '=', 't.user_id')
                ->whereNull('u.id')
                ->delete();
        }

        if (Schema::hasTable('loyalty_coupon_redemptions')) {
            DB::table('loyalty_coupon_redemptions as t')
                ->leftJoin('users as u', 'u.id', '=', 't.user_id')
                ->whereNull('u.id')
                ->delete();
        }

        if (Schema::hasTable('loyalty_share_logs')) {
            DB::table('loyalty_share_logs as t')
                ->leftJoin('users as u', 'u.id', '=', 't.user_id')
                ->whereNull('u.id')
                ->delete();
        }

        if (Schema::hasTable('loyalty_point_transactions')) {
            DB::table('loyalty_point_transactions as t')
                ->leftJoin('clinics as c', 'c.id', '=', 't.clinic_id')
                ->whereNotNull('t.clinic_id')
                ->whereNull('c.id')
                ->update(['t.clinic_id' => null]);
        }

        if (Schema::hasTable('loyalty_point_transactions')) {
            DB::table('loyalty_point_transactions as t')
                ->leftJoin('reservations as r', 'r.id', '=', 't.reservation_id')
                ->whereNotNull('t.reservation_id')
                ->whereNull('r.id')
                ->update(['t.reservation_id' => null]);
        }

        if (Schema::hasTable('loyalty_coupon_redemptions')) {
            DB::table('loyalty_coupon_redemptions as t')
                ->leftJoin('loyalty_reward_coupons as c', 'c.id', '=', 't.coupon_id')
                ->whereNull('c.id')
                ->delete();
        }

        if (Schema::hasTable('loyalty_coupon_redemptions')) {
            DB::table('loyalty_coupon_redemptions as t')
                ->leftJoin('clinics as c', 'c.id', '=', 't.clinic_id')
                ->whereNull('c.id')
                ->delete();
        }

        if (Schema::hasTable('loyalty_coupon_redemptions')) {
            DB::table('loyalty_coupon_redemptions as t')
                ->leftJoin('clinics as c', 'c.id', '=', 't.confirmed_by')
                ->whereNotNull('t.confirmed_by')
                ->whereNull('c.id')
                ->update(['t.confirmed_by' => null]);
        }

        if (Schema::hasTable('loyalty_reward_coupons')) {
            DB::table('loyalty_reward_coupons as t')
                ->leftJoin('clinics as c', 'c.id', '=', 't.clinic_id')
                ->whereNull('c.id')
                ->delete();
        }

        if (Schema::hasTable('loyalty_share_logs')) {
            DB::table('loyalty_share_logs as t')
                ->leftJoin('clinics as c', 'c.id', '=', 't.clinic_id')
                ->whereNotNull('t.clinic_id')
                ->whereNull('c.id')
                ->update(['t.clinic_id' => null]);
        }
    }

    private function addForeignKeys(): void
    {
        $this->addForeignKey('loyalty_point_transactions', 'user_id', 'users', 'id', 'cascade');
        $this->addForeignKey('loyalty_point_transactions', 'clinic_id', 'clinics', 'id', 'set null');
        $this->addForeignKey('loyalty_point_transactions', 'reservation_id', 'reservations', 'id', 'set null');

        $this->addForeignKey('loyalty_reward_coupons', 'clinic_id', 'clinics', 'id', 'cascade');

        $this->addForeignKey('loyalty_coupon_redemptions', 'coupon_id', 'loyalty_reward_coupons', 'id', 'cascade');
        $this->addForeignKey('loyalty_coupon_redemptions', 'user_id', 'users', 'id', 'cascade');
        $this->addForeignKey('loyalty_coupon_redemptions', 'clinic_id', 'clinics', 'id', 'cascade');
        $this->addForeignKey('loyalty_coupon_redemptions', 'confirmed_by', 'clinics', 'id', 'set null');

        $this->addForeignKey('loyalty_share_logs', 'user_id', 'users', 'id', 'cascade');
        $this->addForeignKey('loyalty_share_logs', 'clinic_id', 'clinics', 'id', 'set null');
    }

    private function dropForeignKeys(): void
    {
        $definitions = [
            'loyalty_share_logs' => ['user_id', 'clinic_id'],
            'loyalty_coupon_redemptions' => ['coupon_id', 'user_id', 'clinic_id', 'confirmed_by'],
            'loyalty_reward_coupons' => ['clinic_id'],
            'loyalty_point_transactions' => ['user_id', 'clinic_id', 'reservation_id'],
        ];

        foreach ($definitions as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (!$this->foreignKeyExists($table, $column)) {
                    continue;
                }

                Schema::table($table, function (Blueprint $blueprint) use ($column) {
                    $blueprint->dropForeign([$column]);
                });
            }
        }
    }

    private function addForeignKey(
        string $table,
        string $column,
        string $referencedTable,
        string $referencedColumn,
        string $onDelete
    ): void {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column) || !Schema::hasTable($referencedTable)) {
            return;
        }

        if ($this->foreignKeyExists($table, $column)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($column, $referencedTable, $referencedColumn, $onDelete) {
            $foreign = $blueprint->foreign($column)
                ->references($referencedColumn)
                ->on($referencedTable)
                ->onUpdate('cascade');

            if ($onDelete === 'cascade') {
                $foreign->onDelete('cascade');
            } elseif ($onDelete === 'set null') {
                $foreign->nullOnDelete();
            } else {
                $foreign->onDelete($onDelete);
            }
        });
    }

    private function foreignKeyExists(string $table, string $column): bool
    {
        $database = Schema::getConnection()->getDatabaseName();

        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();
    }
}
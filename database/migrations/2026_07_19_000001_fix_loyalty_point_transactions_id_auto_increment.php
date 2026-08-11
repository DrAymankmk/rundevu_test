<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixLoyaltyPointTransactionsIdAutoIncrement extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('loyalty_point_transactions') || !Schema::hasColumn('loyalty_point_transactions', 'id')) {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM `loyalty_point_transactions` WHERE Field = 'id'");

        if ($column && strpos(strtolower((string) $column->Extra), 'auto_increment') === false) {
            DB::statement('ALTER TABLE `loyalty_point_transactions` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }
    }

    public function down()
    {
        //
    }
}

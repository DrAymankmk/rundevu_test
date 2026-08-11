<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	Schema::table('packages', function (Blueprint $table) {
		$table->longText('features_en')->after('name_ar');
		$table->longText('features_ar')->after('features_en');

        // discount
		$table->string('discount')->after('price');
		// price after price
		$table->string('price_after_discount')->after('discount');
        // free months
		$table->integer('free_months')->after('price_after_discount')->default(0);
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
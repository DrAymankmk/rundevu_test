<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('amount');
            $table->bigInteger('clinic_id')->unsigned();
            $table->bigInteger('accounting_id')->unsigned();
            $table->bigInteger('cost_center_id')->unsigned()->nullable();
            $table->bigInteger('account_id')->unsigned();
            $table->integer('type')->default(1)->comment('1 catch_receipt, 2 receipt');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('accounting_id')->references('id')->on('clinics')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts_trees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('notices')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}

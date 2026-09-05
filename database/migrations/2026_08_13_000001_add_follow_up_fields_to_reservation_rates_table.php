<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFollowUpFieldsToReservationRatesTable extends Migration
{
    public function up()
    {
        Schema::table('reservation_rates', function (Blueprint $table) {
            if (!Schema::hasColumn('reservation_rates', 'follow_up_status')) {
                $table->string('follow_up_status', 20)->default('open')->after('rate_value');
            }
            if (!Schema::hasColumn('reservation_rates', 'follow_up_notes')) {
                $table->text('follow_up_notes')->nullable()->after('follow_up_status');
            }
            if (!Schema::hasColumn('reservation_rates', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('follow_up_notes');
            }
            if (!Schema::hasColumn('reservation_rates', 'resolved_by')) {
                $table->unsignedBigInteger('resolved_by')->nullable()->after('resolved_at');
            }
        });
    }

    public function down()
    {
        Schema::table('reservation_rates', function (Blueprint $table) {
            $columns = ['follow_up_status', 'follow_up_notes', 'resolved_at', 'resolved_by'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('reservation_rates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}

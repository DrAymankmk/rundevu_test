<?php

use App\Models\Clinic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToClinicsTable extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (! Schema::hasColumn('clinics', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }
        });

        Clinic::query()
            ->orderBy('id')
            ->get(['id', 'name', 'slug'])
            ->each(function (Clinic $clinic) {
                if (filled($clinic->slug)) {
                    return;
                }

                $clinic->forceFill([
                    'slug' => Clinic::uniqueSlug($clinic->name ?: ('clinic-'.$clinic->id), $clinic->id),
                ])->saveQuietly();
            });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
}

<?php

use App\Models\Clinic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (! Schema::hasColumn('clinics', 'slug_en')) {
                $table->string('slug_en')->nullable()->after('slug');
            }
        });

        Clinic::query()
            ->orderBy('id')
            ->get(['id', 'name', 'slug', 'slug_en'])
            ->each(function (Clinic $clinic) {
                if (filled($clinic->slug_en)) {
                    return;
                }

                $source = filled($clinic->slug)
                    ? (string) $clinic->slug
                    : (string) ($clinic->name ?: ('clinic-'.$clinic->id));

                $clinic->forceFill([
                    'slug_en' => Clinic::uniqueSlug($source, $clinic->id, 'en'),
                ])->saveQuietly();
            });

        $hasUnique = collect(DB::select("SHOW INDEX FROM clinics WHERE Column_name = 'slug_en' AND Non_unique = 0"))
            ->isNotEmpty();

        if (! $hasUnique && Schema::hasColumn('clinics', 'slug_en')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->unique('slug_en');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'slug_en')) {
                $table->dropUnique(['slug_en']);
                $table->dropColumn('slug_en');
            }
        });
    }
};

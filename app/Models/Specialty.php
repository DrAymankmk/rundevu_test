<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = [
        'name_ar','name_en', 'icon', 'image', 'parent_id','status','created_by'
    ];

    public function sub_specialties()
    {
        return $this->hasMany(Specialty::class,'parent_id')->select('id','name_en as name');
    }

    public function admin()
    {
        return $this->belongsTo(Clinic::class,'created_by');
    }

    public function sub_specialties_list()
    {
        return $this->hasMany(Specialty::class,'parent_id');
    }


    public function clinic_specialties()
    {
        return $this->hasMany(ClinicSpecialist::class,'clinic_id');
    }

    public function imageUrl(): ?string
    {
        $file = $this->attributes['image'] ?? null;
        if (! filled($file)) {
            return null;
        }

        return asset('media/specialties/'.$file);
    }

    public function localizedName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'en'
            ? (string) ($this->name_en ?: $this->name_ar)
            : (string) ($this->name_ar ?: $this->name_en);
    }

    /**
     * Stable public slugs for /doctors/{specialty}, derived from the English name.
     *
     * @return array<int, string>
     */
    public static function frontendSlugMap(): array
    {
        static $map = null;

        if ($map !== null) {
            return $map;
        }

        $used = [];
        $map = [];

        $rows = static::query()
            ->where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get(['id', 'name_en', 'name_ar']);

        foreach ($rows as $row) {
            $base = Clinic::makeSlug((string) ($row->name_en ?: $row->name_ar));
            if ($base === '') {
                $base = 'specialty-'.$row->id;
            }

            $slug = $base;
            $suffix = 2;
            while (isset($used[$slug])) {
                $slug = $base.'-'.$suffix;
                $suffix++;
            }

            $used[$slug] = true;
            $map[(int) $row->id] = $slug;
        }

        return $map;
    }

    public static function assignFrontendSlugs($specialties)
    {
        $map = static::frontendSlugMap();

        foreach ($specialties as $specialty) {
            $specialty->setAttribute(
                'frontend_slug',
                $map[(int) $specialty->id] ?? ('specialty-'.$specialty->id)
            );
        }

        return $specialties;
    }

    public static function findByFrontendSlug(string $slug): ?self
    {
        $slug = strtolower(trim($slug, " \t\n\r\0\x0B/"));
        if ($slug === '') {
            return null;
        }

        $id = array_search($slug, static::frontendSlugMap(), true);
        if ($id === false) {
            return null;
        }

        return static::query()
            ->where('status', 1)
            ->whereNull('parent_id')
            ->find($id);
    }
}

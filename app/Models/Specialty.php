<?php

namespace App\Models;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use HasFactory, SoftDeletes, HasSeo;

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

    /**
     * Small WebP copy of the uploaded image for icon-sized slots (cards render it at 64px).
     * Generated once on first use and stored next to the original; falls back to the
     * original URL whenever a thumbnail cannot be produced.
     */
    public function thumbUrl(int $size = 128): ?string
    {
        $file = $this->attributes['image'] ?? null;
        if (! filled($file)) {
            return null;
        }

        $source = public_path('media/specialties/'.$file);
        $thumbName = pathinfo($file, PATHINFO_FILENAME).'-'.$size.'.webp';
        $thumbRelative = 'media/specialties/thumbs/'.$thumbName;
        $thumb = public_path($thumbRelative);

        try {
            if (! is_file($thumb) && ! self::makeWebpThumb($source, $thumb, $size)) {
                return $this->imageUrl();
            }

            return asset($thumbRelative).'?v='.filemtime($thumb);
        } catch (\Throwable $e) {
            return $this->imageUrl();
        }
    }

    public static function thumbPaths(?string $file): array
    {
        if (! filled($file)) {
            return [];
        }

        return glob(public_path('media/specialties/thumbs/'.pathinfo($file, PATHINFO_FILENAME).'-*.webp')) ?: [];
    }

    private static function makeWebpThumb(string $source, string $target, int $size): bool
    {
        if (! is_file($source) || ! function_exists('imagewebp') || ! function_exists('imagecreatefromstring')) {
            return false;
        }

        $image = @imagecreatefromstring((string) file_get_contents($source));
        if (! $image) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1, $size / max($width, $height));
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $directory = dirname($target);
        if (! is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }

        $saved = @imagewebp($canvas, $target, 82);
        imagedestroy($image);
        imagedestroy($canvas);

        if (! $saved || ! is_file($target) || filesize($target) === 0) {
            @unlink($target);

            return false;
        }

        return true;
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

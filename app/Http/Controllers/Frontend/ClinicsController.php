<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicRating;
use App\Models\Specialty;
use App\Services\Seo\SeoResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClinicsController extends Controller
{
    private const PER_PAGE = 9;
    private const NEAR_RADIUS_KM = 50;

    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->get('q', ''));
        $cityId = (int) $request->get('city_id', 0);
        $specialtyId = (int) $request->get('specialty_id', 0);
        $lat = $request->filled('lat') ? (float) $request->get('lat') : null;
        $lng = $request->filled('lng') ? (float) $request->get('lng') : null;
        $nearMe = $lat !== null && $lng !== null
            && $lat >= -90 && $lat <= 90
            && $lng >= -180 && $lng <= 180;

        $clinics = $this->filteredClinicsQuery($search, $cityId, $specialtyId, $nearMe ? $lat : null, $nearMe ? $lng : null)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $clinics->getCollection()->transform(function (Clinic $clinic) {
            $clinic->rate = ClinicRating::rate($clinic->id);
            $clinic->specialty_names = $this->specialtyNames($clinic);

            return $clinic;
        });

        $cities = City::query()
            ->where('status', 1)
            ->orderBy($locale === 'en' ? 'name_en' : 'name_ar')
            ->get();

        $specialties = Specialty::query()
            ->where('status', 1)
            ->whereNull('parent_id')
            ->orderBy($locale === 'en' ? 'name_en' : 'name_ar')
            ->get();

        $seo = $this->seoResolver->resolve(null, $locale, [
            'title' => __('main.clinics'),
            'description' => __('clinics.frontend_list_description'),
            'canonical' => route('frontend.clinics'),
        ]);

        return view('frontend.pages.clinics.index', compact(
            'clinics',
            'cities',
            'specialties',
            'search',
            'cityId',
            'specialtyId',
            'lat',
            'lng',
            'nearMe',
            'seo'
        ));
    }

    public function show(int $id)
    {
        $locale = app()->getLocale();

        $clinic = Clinic::query()
            ->where('app_type', 1)
            ->where('status', 1)
            ->with([
                'city',
                'specialties.specialties',
                'medical_staff',
                'seoMeta.translations',
            ])
            ->findOrFail($id);

        $clinic->rate = ClinicRating::rate($clinic->id);
        $clinic->rates_count = ClinicRating::where('clinic_id', $clinic->id)
            ->where('comment', '!=', null)
            ->count();
        $clinic->specialty_names = $this->specialtyNames($clinic);
        $info = $locale === 'ar' && filled($clinic->info_ar)
            ? $clinic->info_ar
            : ($clinic->info ?? '');

        $visitorRatings = ClinicRating::with('users')
            ->where('clinic_id', $clinic->id)
            ->where('comment', '!=', null)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $specialtyIds = $clinic->specialties->pluck('specialty_id')->filter()->unique()->values()->all();

        $relatedClinics = collect();
        if ($specialtyIds !== []) {
            $relatedClinics = Clinic::query()
                ->where('app_type', 1)
                ->where('status', 1)
                ->where('id', '!=', $clinic->id)
                ->whereHas('specialties', function (Builder $q) use ($specialtyIds) {
                    $q->whereIn('specialty_id', $specialtyIds);
                })
                ->with(['city', 'specialties.specialties'])
                ->orderByDesc('id')
                ->limit(4)
                ->get()
                ->each(function (Clinic $related) {
                    $related->rate = ClinicRating::rate($related->id);
                    $related->specialty_names = $this->specialtyNames($related);
                });
        }

        $seo = $this->seoResolver->resolve($clinic, $locale, [
            'title' => $clinic->name,
            'description' => \Illuminate\Support\Str::limit(strip_tags((string) $info), 160),
            'image' => $clinic->image,
            'canonical' => route('frontend.clinics.show', $clinic->id),
        ]);

        return view('frontend.pages.clinics.show', compact(
            'clinic',
            'info',
            'visitorRatings',
            'relatedClinics',
            'seo'
        ));
    }

    private function filteredClinicsQuery(string $search, int $cityId, int $specialtyId, ?float $lat, ?float $lng): Builder
    {
        if ($lat !== null && $lng !== null) {
            $distanceSql = '(6367 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat))))';

            $query = Clinic::query()
                ->select('clinics.*')
                ->selectRaw($distanceSql . ' AS distance', [$lat, $lng, $lat])
                ->where('app_type', 1)
                ->where('status', 1)
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->where('lat', '!=', 0)
                ->where('lng', '!=', 0)
                ->whereRaw($distanceSql . ' < ?', [$lat, $lng, $lat, self::NEAR_RADIUS_KM])
                ->with(['city', 'specialties.specialties'])
                ->orderBy('distance');
        } else {
            $query = Clinic::query()
                ->where('app_type', 1)
                ->where('status', 1)
                ->with(['city', 'specialties.specialties'])
                ->orderBy('name');
        }

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('info', 'like', "%{$search}%")
                    ->orWhere('info_ar', 'like', "%{$search}%");
            });
        }

        if ($cityId > 0) {
            $query->where('city_id', $cityId);
        }

        if ($specialtyId > 0) {
            $query->whereHas('specialties', function (Builder $q) use ($specialtyId) {
                $q->where('specialty_id', $specialtyId);
            });
        }

        return $query;
    }

    private function specialtyNames(Clinic $clinic): string
    {
        $locale = app()->getLocale();

        return $clinic->specialties
            ->map(function ($row) use ($locale) {
                $specialty = $row->specialties;
                if (!$specialty) {
                    return null;
                }

                return $locale === 'en'
                    ? ($specialty->name_en ?: $specialty->name_ar)
                    : ($specialty->name_ar ?: $specialty->name_en);
            })
            ->filter()
            ->unique()
            ->implode(', ');
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ReservationRate;
use App\Models\Specialty;
use App\Services\Seo\SeoResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    private const PER_PAGE = 9;

    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->get('q', ''));
        $cityId = (int) $request->get('city_id', 0);
        $specialtyId = (int) $request->get('specialty_id', 0);
        $clinicId = (int) $request->get('clinic_id', 0);

        $doctors = $this->filteredDoctorsQuery($search, $cityId, $specialtyId, $clinicId)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $doctors->getCollection()->transform(function (Clinic $doctor) {
            return $this->decorateDoctor($doctor);
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

        $clinics = Clinic::query()
            ->where('app_type', 1)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $seo = $this->seoResolver->resolve(null, $locale, [
            'title' => __('doctors.page_title'),
            'description' => __('doctors.frontend_list_description'),
            'canonical' => route('frontend.doctors'),
        ]);

        return view('frontend.pages.doctors.index', compact(
            'doctors',
            'cities',
            'specialties',
            'clinics',
            'search',
            'cityId',
            'specialtyId',
            'clinicId',
            'seo'
        ));
    }

    public function show(int $id)
    {
        $locale = app()->getLocale();

        $doctor = Clinic::query()
            ->where('app_type', 3)
            ->where('status', 1)
            ->with([
                'owner.city',
                'city',
                'degree',
                'specialties.specialties',
                'sub_specialties.specialties',
                'seoMeta.translations',
            ])
            ->whereHas('owner', function (Builder $q) {
                $q->where('app_type', 1)->where('status', 1);
            })
            ->findOrFail($id);

        $this->decorateDoctor($doctor);

        $info = $locale === 'ar' && filled($doctor->info_ar)
            ? $doctor->info_ar
            : ($doctor->info ?? '');

        $visitorRatings = ReservationRate::with('users')
            ->where('doctor_id', $doctor->id)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $specialtyIds = $doctor->specialties->pluck('specialty_id')->filter()->unique()->values()->all();

        $relatedDoctors = collect();
        if ($specialtyIds !== []) {
            $relatedDoctors = Clinic::query()
                ->where('app_type', 3)
                ->where('status', 1)
                ->where('id', '!=', $doctor->id)
                ->whereHas('owner', function (Builder $q) {
                    $q->where('app_type', 1)->where('status', 1);
                })
                ->whereHas('specialties', function (Builder $q) use ($specialtyIds) {
                    $q->whereIn('specialty_id', $specialtyIds);
                })
                ->with(['owner', 'degree', 'specialties.specialties'])
                ->orderByDesc('id')
                ->limit(4)
                ->get()
                ->each(function (Clinic $related) {
                    $this->decorateDoctor($related);
                });
        }

        $seo = $this->seoResolver->resolve($doctor, $locale, [
            'title' => $doctor->name,
            'description' => \Illuminate\Support\Str::limit(strip_tags((string) $info), 160),
            'image' => $doctor->image,
            'canonical' => route('frontend.doctors.show', $doctor->id),
        ]);

        return view('frontend.pages.doctors.show', compact(
            'doctor',
            'info',
            'visitorRatings',
            'relatedDoctors',
            'seo'
        ));
    }

    private function filteredDoctorsQuery(string $search, int $cityId, int $specialtyId, int $clinicId): Builder
    {
        $query = Clinic::query()
            ->where('app_type', 3)
            ->where('status', 1)
            ->whereHas('owner', function (Builder $q) {
                $q->where('app_type', 1)->where('status', 1);
            })
            ->with(['owner.city', 'city', 'degree', 'specialties.specialties'])
            ->orderBy('name');

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('info', 'like', "%{$search}%")
                    ->orWhere('info_ar', 'like', "%{$search}%")
                    ->orWhereHas('owner', function (Builder $ownerQuery) use ($search) {
                        $ownerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($cityId > 0) {
            $query->where(function (Builder $q) use ($cityId) {
                $q->where('city_id', $cityId)
                    ->orWhereHas('owner', function (Builder $ownerQuery) use ($cityId) {
                        $ownerQuery->where('city_id', $cityId);
                    });
            });
        }

        if ($specialtyId > 0) {
            $query->whereHas('specialties', function (Builder $q) use ($specialtyId) {
                $q->where('specialty_id', $specialtyId);
            });
        }

        if ($clinicId > 0) {
            $query->where('parent_id', $clinicId);
        }

        return $query;
    }

    private function decorateDoctor(Clinic $doctor): Clinic
    {
        $doctor->rate = $this->doctorRate($doctor->id);
        $doctor->rates_count = ReservationRate::where('doctor_id', $doctor->id)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->count();
        $doctor->specialty_names = $this->specialtyNames($doctor);
        $doctor->degree_name = $this->degreeName($doctor);

        return $doctor;
    }

    private function doctorRate(int $doctorId): string
    {
        $avg = ReservationRate::where('doctor_id', $doctorId)->avg('rate_value');

        return $avg !== null ? (string) round((float) $avg, 1) : '0';
    }

    private function specialtyNames(Clinic $doctor): string
    {
        $locale = app()->getLocale();

        return $doctor->specialties
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

    private function degreeName(Clinic $doctor): string
    {
        if (!$doctor->degree) {
            return '';
        }

        $locale = app()->getLocale();

        return $locale === 'en'
            ? ($doctor->degree->name_en ?: $doctor->degree->name_ar ?: '')
            : ($doctor->degree->name_ar ?: $doctor->degree->name_en ?: '');
    }
}

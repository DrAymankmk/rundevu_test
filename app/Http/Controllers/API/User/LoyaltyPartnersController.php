<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Models\AppType;
use App\Models\Clinic;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class LoyaltyPartnersController extends APIController
{
    public $repository;

    public function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    public function sections(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $appTypeIds = array_values(config('organization_fields.app_types', [1, 4, 5, 7]));
        $lang = $request->header('lang') === 'en' ? 'en' : 'ar';
        $nameColumn = $lang === 'en' ? 'name_en' : 'name_ar';

        $sections = AppType::whereIn('id', $appTypeIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $appTypeIds) . ')')
            ->get(['id', 'name_en', 'name_ar'])
            ->map(function ($section) use ($request, $nameColumn) {
                $organizations = Clinic::where('app_type', $section->id)
                    ->where('status', 1)
                    ->where('points_enabled', 1)
                    ->select(
                        'id',
                        'app_type',
                        'name',
                        'image',
                        'phone',
                        'city_id',
                        'address',
                        'lat',
                        'lng',
                        'info',
                        'specialization',
                        'points_category',
                        'enabled_modules'
                    )
                    ->orderBy('name')
                    ->get()
                    ->map(function ($organization) {
                        return [
                            'id' => $organization->id,
                            'name' => $organization->name,
                            'logo' => $organization->image,
                            'phone' => (string) ($organization->phone ?? ''),
                            'address' => (string) ($organization->address ?? ''),
                            'lat' => (string) ($organization->lat ?? '0.0'),
                            'lng' => (string) ($organization->lng ?? '0.0'),
                            'info' => (string) ($organization->info ?? ''),
                            'specialization' => (string) ($organization->specialization ?? ''),
                            'points_category' => (string) ($organization->points_category ?? ''),
                            'enabled_modules' => $organization->selectedModuleKeys(),
                            'usage' => [
                                'points_enabled' => (bool) $organization->points_enabled,
                                'can_earn_points' => in_array('points', $organization->displayModuleKeys(), true),
                                'can_redeem_rewards' => in_array('coupons', $organization->displayModuleKeys(), true),
                            ],
                        ];
                    })
                    ->values();

                return [
                    'id' => $section->id,
                    'name' => $section->{$nameColumn},
                    'name_ar' => $section->name_ar,
                    'name_en' => $section->name_en,
                    'organizations_count' => $organizations->count(),
                    'organizations' => $organizations,
                ];
            })
            ->filter(function ($section) use ($request) {
                return $request->boolean('include_empty_sections') || $section['organizations_count'] > 0;
            })
            ->values();

        return $this->success('Loyalty partner sections', $sections);
    }
}

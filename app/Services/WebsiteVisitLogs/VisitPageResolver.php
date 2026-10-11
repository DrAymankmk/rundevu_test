<?php

namespace App\Services\WebsiteVisitLogs;

use App\Models\BlogPost;
use App\Models\Clinic;
use App\Models\Specialty;
use Illuminate\Http\Request;

class VisitPageResolver
{
    /**
     * @return array{
     *   page_type: string,
     *   page_path: string,
     *   page_url: string,
     *   entity_type: string|null,
     *   entity_id: int|null,
     *   entity_slug: string|null,
     *   locale: string|null
     * }
     */
    public function resolve(Request $request): array
    {
        $path = VisitUrlDisplay::decode('/' . ltrim($request->path(), '/')) ?: '/';
        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        $pageUrl = $path;
        $query = VisitUrlDisplay::decode($request->getQueryString());
        if ($query) {
            $pageUrl .= '?' . $query;
        }

        $routeName = (string) optional($request->route())->getName();
        $baseName = preg_replace('/^frontend\.(ar\.)?/', '', $routeName) ?: '';

        $result = [
            'page_type' => 'other',
            'page_path' => mb_substr($path, 0, 512),
            'page_url' => mb_substr($pageUrl, 0, 2048),
            'entity_type' => null,
            'entity_id' => null,
            'entity_slug' => null,
            'locale' => (strpos($routeName, 'frontend.ar.') === 0 || strpos($path, '/ar') === 0 || strpos($path, '/ar/') === 0)
                ? 'ar'
                : (app()->getLocale() ?: 'en'),
        ];

        switch ($baseName) {
            case 'home':
                $result['page_type'] = 'home';
                break;
            case 'about':
                $result['page_type'] = 'about';
                break;
            case 'services':
                $result['page_type'] = 'services';
                break;
            case 'faq':
                $result['page_type'] = 'faq';
                break;
            case 'subscription':
                $result['page_type'] = 'subscription';
                break;
            case 'contact':
                $result['page_type'] = 'contact';
                break;
            case 'blog':
                $result['page_type'] = 'blog';
                break;
            case 'blog.show':
                $result = array_merge($result, $this->resolveBlogShow($request));
                break;
            case 'clinics':
                $result['page_type'] = 'clinics';
                break;
            case 'clinics.show':
                $result = array_merge($result, $this->resolveClinicShow($request));
                break;
            case 'doctors':
                $result['page_type'] = 'doctors';
                break;
            case 'doctors.show':
                $result = array_merge($result, $this->resolveDoctorShow($request));
                break;
            case 'social':
                $result['page_type'] = 'social';
                break;
            default:
                $result['page_type'] = 'other';
                break;
        }

        return $result;
    }

    private function resolveBlogShow(Request $request): array
    {
        $slug = (string) ($request->route('slug') ?? '');
        $data = [
            'page_type' => 'blog_show',
            'entity_type' => 'blog_post',
            'entity_id' => null,
            'entity_slug' => $slug !== '' ? mb_substr(VisitUrlDisplay::decode($slug) ?: $slug, 0, 255) : null,
        ];

        if ($slug === '') {
            return $data;
        }

        try {
            $post = BlogPost::query()
                ->published()
                ->whereSlug($slug)
                ->first(['id', 'slug', 'name']);

            if ($post) {
                $data['entity_id'] = (int) $post->id;
                $routeSlug = (string) ($post->getRouteSlug() ?: $post->slug ?: $slug);
                $data['entity_slug'] = mb_substr(VisitUrlDisplay::decode($routeSlug) ?: $routeSlug, 0, 255);
            }
        } catch (\Throwable $e) {
            // keep slug-only data
        }

        return $data;
    }

    private function resolveClinicShow(Request $request): array
    {
        $param = $request->route('clinic');
        $data = [
            'page_type' => 'clinic_show',
            'entity_type' => 'clinic',
            'entity_id' => null,
            'entity_slug' => null,
        ];

        if ($param instanceof Clinic) {
            $data['entity_id'] = (int) $param->id;
            $data['entity_slug'] = $this->readableSlug((string) ($param->localizedSlug() ?: $param->id));

            return $data;
        }

        $slug = VisitUrlDisplay::decode((string) $param) ?: (string) $param;
        $data['entity_slug'] = $slug !== '' ? $this->readableSlug($slug) : null;

        if ($slug === '') {
            return $data;
        }

        try {
            $clinic = Clinic::findByLocalizedSlug($slug);

            if ($clinic && (int) $clinic->app_type === 1) {
                $data['entity_id'] = (int) $clinic->id;
                $data['entity_slug'] = $this->readableSlug((string) ($clinic->localizedSlug() ?: $slug));
            }
        } catch (\Throwable $e) {
            // keep slug-only data
        }

        return $data;
    }

    private function resolveDoctorShow(Request $request): array
    {
        $param = VisitUrlDisplay::decode((string) ($request->route('doctor') ?? '')) ?: (string) ($request->route('doctor') ?? '');
        $data = [
            'page_type' => 'doctors',
            'entity_type' => null,
            'entity_id' => null,
            'entity_slug' => $param !== '' ? $this->readableSlug($param) : null,
        ];

        if ($param === '') {
            return $data;
        }

        try {
            $specialty = Specialty::findByFrontendSlug($param);
            if ($specialty) {
                $data['page_type'] = 'doctors';
                $data['entity_type'] = 'specialty';
                $data['entity_id'] = (int) $specialty->id;
                $data['entity_slug'] = $this->readableSlug($param);

                return $data;
            }

            $doctor = Clinic::findByLocalizedSlug($param);
            if (! $doctor && ctype_digit($param)) {
                $doctor = Clinic::query()->find((int) $param);
            }

            if ($doctor && (int) $doctor->app_type === 3) {
                $data['page_type'] = 'doctor_show';
                $data['entity_type'] = 'doctor';
                $data['entity_id'] = (int) $doctor->id;
                $data['entity_slug'] = $this->readableSlug((string) ($doctor->localizedSlug() ?: $param));
            }
        } catch (\Throwable $e) {
            // keep path-only data
        }

        return $data;
    }

    private function readableSlug(string $slug): string
    {
        return mb_substr(VisitUrlDisplay::decode($slug) ?: $slug, 0, 255);
    }
}

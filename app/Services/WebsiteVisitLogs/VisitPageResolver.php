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
        return $this->resolvePath(
            '/' . ltrim($request->path(), '/'),
            $request->getQueryString(),
            (string) optional($request->route())->getName(),
            $request
        );
    }

    /**
     * Resolve visit metadata from a client-reported path (beacon bootstrap).
     *
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
    public function resolveFromClientPath(string $pagePath, ?string $pageUrl = null): array
    {
        $parts = parse_url($pageUrl ?: $pagePath);
        $path = is_array($parts) && ! empty($parts['path']) ? $parts['path'] : $pagePath;
        $query = is_array($parts) && ! empty($parts['query']) ? $parts['query'] : null;

        return $this->resolvePath($path, $query, '', null, $pageUrl);
    }

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
    private function resolvePath(
        string $rawPath,
        ?string $query,
        string $routeName,
        ?Request $request = null,
        ?string $absoluteUrl = null
    ): array {
        $path = VisitUrlDisplay::decode('/' . ltrim($rawPath, '/')) ?: '/';
        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        $pageUrl = $absoluteUrl
            ? (VisitUrlDisplay::decode($absoluteUrl) ?: $absoluteUrl)
            : $path;
        if (! $absoluteUrl) {
            $decodedQuery = VisitUrlDisplay::decode($query);
            if ($decodedQuery) {
                $pageUrl .= '?' . $decodedQuery;
            }
        }

        $baseName = preg_replace('/^frontend\.(ar\.)?/', '', $routeName) ?: '';
        if ($baseName === '') {
            $baseName = $this->inferBaseNameFromPath($path);
        }

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
                $result = array_merge($result, $this->resolveBlogShow($request, $path));
                break;
            case 'clinics':
                $result['page_type'] = 'clinics';
                break;
            case 'clinics.show':
                $result = array_merge($result, $this->resolveClinicShow($request, $path));
                break;
            case 'doctors':
                $result['page_type'] = 'doctors';
                break;
            case 'doctors.show':
                $result = array_merge($result, $this->resolveDoctorShow($request, $path));
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

    private function inferBaseNameFromPath(string $path): string
    {
        $trimmed = trim($path, '/');
        if ($trimmed === '' || $trimmed === 'ar') {
            return 'home';
        }

        if (strpos($trimmed, 'ar/') === 0) {
            $trimmed = substr($trimmed, 3);
        }

        $segments = $trimmed === '' ? [] : explode('/', $trimmed);
        $first = $segments[0] ?? '';
        $second = $segments[1] ?? null;

        $map = [
            'about' => 'about',
            'services' => 'services',
            'faq' => 'faq',
            'subscription' => 'subscription',
            'contact' => 'contact',
            'blog' => $second !== null ? 'blog.show' : 'blog',
            'clinics' => $second !== null ? 'clinics.show' : 'clinics',
            'doctors' => $second !== null ? 'doctors.show' : 'doctors',
            'social-media' => 'social',
        ];

        return $map[$first] ?? 'other';
    }

    private function resolveBlogShow(?Request $request, string $path = ''): array
    {
        $slug = (string) (optional($request)->route('slug') ?? '');
        if ($slug === '' && $path !== '') {
            $parts = explode('/', trim($path, '/'));
            if (($parts[0] ?? '') === 'ar') {
                array_shift($parts);
            }
            if (($parts[0] ?? '') === 'blog') {
                $slug = (string) ($parts[1] ?? '');
            } elseif (! empty($parts[0]) && ($parts[0] ?? '') !== 'blog') {
                // Arabic flat blog: /ar/{slug}
                $slug = (string) $parts[0];
            }
        }
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

    private function resolveClinicShow(?Request $request, string $path = ''): array
    {
        $param = optional($request)->route('clinic');
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

        if (($param === null || $param === '') && $path !== '') {
            $parts = explode('/', trim($path, '/'));
            if (($parts[0] ?? '') === 'ar') {
                array_shift($parts);
            }
            $param = ($parts[0] ?? '') === 'clinics' ? ($parts[1] ?? '') : '';
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

    private function resolveDoctorShow(?Request $request, string $path = ''): array
    {
        $raw = (string) (optional($request)->route('doctor') ?? '');
        if ($raw === '' && $path !== '') {
            $parts = explode('/', trim($path, '/'));
            if (($parts[0] ?? '') === 'ar') {
                array_shift($parts);
            }
            $raw = ($parts[0] ?? '') === 'doctors' ? (string) ($parts[1] ?? '') : '';
        }
        $param = VisitUrlDisplay::decode($raw) ?: $raw;
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

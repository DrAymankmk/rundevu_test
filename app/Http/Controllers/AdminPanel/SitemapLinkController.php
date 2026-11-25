<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SitemapController;
use App\Models\SitemapLink;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SitemapLinkController extends Controller
{
    public function index(SitemapController $sitemapController)
    {
        $links = SitemapLink::ordered()->get();
        $pages = $links->where('link_type', SitemapLink::TYPE_PAGE)->values();
        $groups = $links->where('link_type', SitemapLink::TYPE_GROUP)->values();
        $sitemapCounts = [
            'en' => $sitemapController->urlCount('en'),
            'ar' => $sitemapController->urlCount('ar'),
        ];

        return view('main_admin.sitemap_links.index', compact('pages', 'groups', 'sitemapCounts'));
    }

    public function create()
    {
        return view('main_admin.sitemap_links.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        SitemapLink::create([
            'title' => $validated['title'],
            'link_type' => SitemapLink::TYPE_PAGE,
            'group_key' => null,
            'path' => $validated['path'],
            'changefreq' => $validated['changefreq'],
            'priority' => $validated['priority'],
            'lastmod' => $validated['lastmod'] ?? null,
            'include_alternates' => $request->boolean('include_alternates'),
            'x_default_locale' => $validated['x_default_locale'] ?? SitemapLink::X_DEFAULT_AUTO,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? ((int) SitemapLink::max('sort_order') + 1),
        ]);

        return redirect()->route('sitemap-links.index')
            ->with('success', __('sitemap_links.created'));
    }

    public function edit($id)
    {
        $link = SitemapLink::findOrFail($id);

        return view('main_admin.sitemap_links.edit', compact('link'));
    }

    public function update(Request $request, $id)
    {
        $link = SitemapLink::findOrFail($id);
        $validated = $this->validated($request, $link);

        $payload = [
            'title' => $validated['title'],
            'changefreq' => $validated['changefreq'],
            'priority' => $validated['priority'],
            'lastmod' => $validated['lastmod'] ?? null,
            'include_alternates' => $request->boolean('include_alternates'),
            'x_default_locale' => $validated['x_default_locale'] ?? SitemapLink::X_DEFAULT_AUTO,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? $link->sort_order,
        ];

        if ($link->isPage()) {
            $payload['path'] = $validated['path'];
            $payload['link_type'] = SitemapLink::TYPE_PAGE;
            $payload['group_key'] = null;
        }

        $link->update($payload);

        return redirect()->route('sitemap-links.index')
            ->with('success', __('sitemap_links.updated'));
    }

    public function destroy($id)
    {
        $link = SitemapLink::findOrFail($id);

        if ($link->isGroup()) {
            return redirect()->route('sitemap-links.index')
                ->with('error', __('sitemap_links.cannot_delete_group'));
        }

        $link->delete();

        return redirect()->route('sitemap-links.index')
            ->with('success', __('sitemap_links.deleted'));
    }

    public function toggleStatus($id)
    {
        $link = SitemapLink::findOrFail($id);
        $link->update(['is_active' => ! $link->is_active]);

        return redirect()->route('sitemap-links.index')
            ->with('success', __('sitemap_links.status_updated'));
    }

    private function validated(Request $request, ?SitemapLink $existing = null): array
    {
        $isGroup = $existing?->isGroup() ?? false;

        $path = trim((string) $request->input('path'));
        if (! $isGroup && $path !== '' && ! preg_match('/^https?:\/\//i', $path)) {
            $path = '/' . ltrim($path, '/');
            if ($path === '//') {
                $path = '/';
            }
        }

        $lastmod = trim((string) $request->input('lastmod'));

        $request->merge([
            'path' => $path === '' ? null : $path,
            'lastmod' => $lastmod === '' ? null : $lastmod,
            'priority' => $request->input('priority') !== null && $request->input('priority') !== ''
                ? number_format((float) $request->input('priority'), 1, '.', '')
                : null,
        ]);

        $xDefaultAllowed = array_merge(
            [SitemapLink::X_DEFAULT_AUTO, SitemapLink::X_DEFAULT_OMIT],
            (array) config('app.locales', ['en', 'ar'])
        );

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'changefreq' => ['required', Rule::in(SitemapLink::CHANGEFREQ_OPTIONS)],
            'priority' => ['required', 'numeric', 'min:0', 'max:1'],
            'lastmod' => ['nullable', 'date'],
            'include_alternates' => ['nullable', 'boolean'],
            'x_default_locale' => ['nullable', 'string', Rule::in($xDefaultAllowed)],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];

        if (! $isGroup) {
            $rules['path'] = [
                'required',
                'string',
                'max:500',
                Rule::unique('sitemap_links', 'path')->ignore($existing?->id)->where(function ($query) {
                    $query->where('link_type', SitemapLink::TYPE_PAGE);
                }),
            ];
        }

        return $request->validate($rules);
    }
}

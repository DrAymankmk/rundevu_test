<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\CmsLanguage;
use App\Models\WebsiteLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WebsiteLinkController extends Controller
{
    public function index()
    {
        $links = WebsiteLink::with('translations')->ordered()->get();
        $storeLinks = $links->where('type', WebsiteLink::TYPE_STORE)->values();
        $socialLinks = $links->where('type', WebsiteLink::TYPE_SOCIAL)->values();

        return view('main_admin.website_links.index', compact('storeLinks', 'socialLinks'));
    }

    public function create(Request $request)
    {
        $languages = $this->languages();
        $presets = WebsiteLink::presets();
        $type = $request->input('type', WebsiteLink::TYPE_SOCIAL);
        if (! in_array($type, [WebsiteLink::TYPE_SOCIAL, WebsiteLink::TYPE_STORE], true)) {
            $type = WebsiteLink::TYPE_SOCIAL;
        }

        return view('main_admin.website_links.create', compact('languages', 'presets', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $link = WebsiteLink::create([
            'key' => $validated['key'],
            'type' => $validated['type'],
            'url' => $validated['url'] ?: null,
            'icon' => $validated['icon'] ?: ($this->presetValue($validated['key'], 'icon')),
            'brand_color' => $validated['brand_color'] ?: ($this->presetValue($validated['key'], 'brand_color') ?: '#3E66F3'),
            'sort_order' => $validated['sort_order'] ?? ((int) WebsiteLink::max('sort_order') + 1),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncTranslations($link, $validated['translations']);

        return redirect()->route('website-links.index')
            ->with('success', __('website_links.created'));
    }

    public function edit($id)
    {
        $link = WebsiteLink::with('translations')->findOrFail($id);
        $languages = $this->languages();
        $presets = WebsiteLink::presets();

        return view('main_admin.website_links.edit', compact('link', 'languages', 'presets'));
    }

    public function update(Request $request, $id)
    {
        $link = WebsiteLink::findOrFail($id);
        $validated = $this->validated($request, $link->id);

        $link->update([
            'key' => $validated['key'],
            'type' => $validated['type'],
            'url' => $validated['url'] ?: null,
            'icon' => $validated['icon'] ?: ($this->presetValue($validated['key'], 'icon')),
            'brand_color' => $validated['brand_color'] ?: ($this->presetValue($validated['key'], 'brand_color') ?: '#3E66F3'),
            'sort_order' => $validated['sort_order'] ?? $link->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncTranslations($link, $validated['translations']);

        return redirect()->route('website-links.index')
            ->with('success', __('website_links.updated'));
    }

    public function destroy($id)
    {
        WebsiteLink::findOrFail($id)->delete();

        return redirect()->route('website-links.index')
            ->with('success', __('website_links.deleted'));
    }

    public function toggleStatus($id)
    {
        $link = WebsiteLink::findOrFail($id);
        $link->update(['is_active' => ! $link->is_active]);

        return redirect()->route('website-links.index')
            ->with('success', __('website_links.status_updated'));
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $key = Str::slug((string) $request->input('key'), '_');
        if ($key === '') {
            $key = strtolower(trim((string) $request->input('key')));
        }

        $url = trim((string) $request->input('url'));
        if ($url !== '' && ! preg_match('/^(https?:\/\/|mailto:|tel:|whatsapp:)/i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        $request->merge([
            'key' => $key,
            'url' => $url === '' ? null : $url,
        ]);

        return $request->validate([
            'key' => [
                'required',
                'string',
                'max:80',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('website_links', 'key')->ignore($ignoreId),
            ],
            'type' => ['required', Rule::in([WebsiteLink::TYPE_SOCIAL, WebsiteLink::TYPE_STORE])],
            'url' => ['nullable', 'string', 'max:2048', 'regex:/^(https?:\/\/|mailto:|tel:|whatsapp:).+/i'],
            'icon' => ['nullable', 'string', 'max:120'],
            'brand_color' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string', 'max:1000'],
        ], [
            'key.regex' => __('website_links.key_hint'),
            'url.regex' => __('website_links.url_hint'),
        ]);
    }

    private function syncTranslations(WebsiteLink $link, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $link->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                ]
            );
        }
    }

    private function languages()
    {
        $languages = CmsLanguage::active()->ordered()->get();
        if ($languages->isNotEmpty()) {
            return $languages;
        }

        return collect([
            (object) [
                'code' => 'en',
                'name' => 'English',
                'direction' => 'ltr',
                'flag' => '',
                'is_default' => true,
            ],
            (object) [
                'code' => 'ar',
                'name' => 'Arabic',
                'direction' => 'rtl',
                'flag' => '',
                'is_default' => false,
            ],
        ]);
    }

    private function presetValue(string $key, string $field): ?string
    {
        return WebsiteLink::presets()[$key][$field] ?? null;
    }
}

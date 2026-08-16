<?php

namespace App\Services\Seo;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SeoSyncService
{
    public static function validationRules(): array
    {
        return [
            'seo.canonical_url' => 'nullable|url|max:2048',
            'seo.robots' => 'nullable|string|max:50',
            'seo.og_type' => 'nullable|string|max:50',
            'seo.is_active' => 'nullable|boolean',
            'seo.translations.*.meta_title' => 'nullable|string|max:255',
            'seo.translations.*.meta_description' => 'nullable|string|max:5000',
            'seo.translations.*.meta_keywords' => 'nullable|string|max:255',
            'seo.translations.*.og_title' => 'nullable|string|max:255',
            'seo.translations.*.og_description' => 'nullable|string|max:5000',
            'seo.translations.*.og_image' => 'nullable|string|max:2048',
            'seo.translations.*.twitter_title' => 'nullable|string|max:255',
            'seo.translations.*.twitter_description' => 'nullable|string|max:5000',
            'seo.translations.*.twitter_image' => 'nullable|string|max:2048',
            'seo.translations.*.twitter_card' => 'nullable|string|max:50',
            'seo.translations.*.schema_json' => 'nullable|string',
            'seo.og_image_file' => 'nullable|image|max:5120',
        ];
    }

    public function sync(Model $model, Request $request): ?SeoMeta
    {
        if (!$request->has('seo')) {
            return $model->seoMeta;
        }

        $seoData = $request->input('seo', []);
        $translations = $seoData['translations'] ?? [];

        $seo = $model->seoMeta()->updateOrCreate(
            [
                'seoable_id' => $model->getKey(),
                'seoable_type' => $model->getMorphClass(),
            ],
            [
                'canonical_url' => $seoData['canonical_url'] ?? null,
                'robots' => $seoData['robots'] ?? 'index,follow',
                'og_type' => $seoData['og_type'] ?? 'website',
                'is_active' => $request->boolean('seo.is_active'),
            ]
        );

        foreach ($translations as $locale => $row) {
            $schemaJson = null;
            if (!empty($row['schema_json'])) {
                $decoded = json_decode($row['schema_json'], true);
                $schemaJson = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
            }

            $seo->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'meta_title' => $row['meta_title'] ?? null,
                    'meta_description' => $row['meta_description'] ?? null,
                    'meta_keywords' => $row['meta_keywords'] ?? null,
                    'og_title' => $row['og_title'] ?? null,
                    'og_description' => $row['og_description'] ?? null,
                    'og_image' => $row['og_image'] ?? null,
                    'twitter_title' => $row['twitter_title'] ?? null,
                    'twitter_description' => $row['twitter_description'] ?? null,
                    'twitter_image' => $row['twitter_image'] ?? null,
                    'twitter_card' => $row['twitter_card'] ?? 'summary_large_image',
                    'schema_json' => $schemaJson,
                ]
            );
        }

        if ($request->hasFile('seo.og_image_file')) {
            $seo->clearMediaCollection('og_image');
            $seo->addMediaFromRequest('seo.og_image_file')->toMediaCollection('og_image');
        }

        return $seo;
    }
}

<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\Frontend\FrontendMediaCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrontendMediaController extends Controller
{
    /** @var FrontendMediaCatalog */
    private $catalog;

    public function __construct(FrontendMediaCatalog $catalog)
    {
        $this->catalog = $catalog;
    }

    public function index()
    {
        return view('main_admin.media.index');
    }

    public function data(Request $request): JsonResponse
    {
        $result = $this->catalog->paginate(
            [
                'source' => $request->input('source'),
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'has_image' => $request->input('has_image'),
            ],
            (int) $request->input('page', 1),
            (int) $request->input('per_page', FrontendMediaCatalog::PER_PAGE)
        );

        return response()->json($result);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,svg,mp4,webm,mov|max:10240',
        ]);

        try {
            $item = $this->catalog->update($request->input('key'), $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('website_media.update_failed'),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => __('website_media.updated'),
            'item' => $item,
        ]);
    }

    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string|max:255',
        ]);

        try {
            $item = $this->catalog->convertToRecommendedSize($request->input('key'));
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('website_media.convert_failed'),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => __('website_media.converted', [
                'size' => $item['hint']['label'] ?? '',
            ]),
            'item' => $item,
        ]);
    }
}

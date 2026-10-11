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
        return view('main_admin.media.index', [
            'maxUploadKb' => FrontendMediaCatalog::maxUploadKilobytes(),
        ]);
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
        if ($invalid = $this->invalidUploadResponse($request)) {
            return $invalid;
        }

        $maxKb = FrontendMediaCatalog::maxUploadKilobytes();
        $maxLabel = FrontendMediaCatalog::maxUploadLabel();
        $request->validate([
            'key' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,svg,mp4,webm,mov|max:' . $maxKb,
        ], [
            'file.uploaded' => __('website_media.file_too_large', ['max' => $maxLabel]),
            'file.max' => __('website_media.file_too_large', ['max' => $maxLabel]),
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

    private function invalidUploadResponse(Request $request): ?JsonResponse
    {
        $max = FrontendMediaCatalog::maxUploadLabel();
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        if ($contentLength > 0 && empty($_FILES) && empty($_POST)) {
            return $this->uploadErrorResponse(__('website_media.file_too_large', ['max' => $max]));
        }

        $file = $request->file('file');
        if ($file && $file->isValid()) {
            return null;
        }

        $error = $file ? $file->getError() : ($_FILES['file']['error'] ?? null);
        if ($error === null || (int) $error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $message = match ((int) $error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => __('website_media.file_too_large', ['max' => $max]),
            UPLOAD_ERR_PARTIAL => __('website_media.upload_partial'),
            UPLOAD_ERR_NO_TMP_DIR => __('website_media.upload_tmp'),
            UPLOAD_ERR_CANT_WRITE => __('website_media.upload_write'),
            default => __('website_media.upload_failed', ['max' => $max]),
        };

        return $this->uploadErrorResponse($message);
    }

    private function uploadErrorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => ['file' => [$message]],
        ], 422);
    }
}

<?php

namespace App\Http\Controllers\AdminPanel\CMS;

use App\Models\MediaLibrary;
use App\Models\CmsSection;
use App\Support\Cms\CmsGalleryMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Controllers\Controller;
class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cms.media.index');
    }

    /**
     * Get media data for DataTable.
     */
    public function data(Request $request): JsonResponse
    {
        $query = Media::query();

        // Filter by collection
        if ($request->has('collection') && $request->collection) {
            $query->where('collection_name', $request->collection);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('mime_type', 'like', $request->type.'%');
        }

        // Search
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%")
                    ->orWhere('mime_type', 'like', "%{$search}%");
            });
        }

        $totalRecords = Media::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $columnDirection = $request->order[0]['dir'];

            $sortableColumns = ['id', 'name', 'file_name', 'mime_type', 'size', 'created_at'];
            if (in_array($columnName, $sortableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $media = $query->skip($start)->take($length)->get();

        $data = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file_name' => $item->file_name,
                'mime_type' => $item->mime_type,
                'size' => $this->formatBytes($item->size),
                'size_raw' => $item->size,
                'collection_name' => $item->collection_name,
                'model_type' => $item->model_type,
                'model_type_display' => class_basename($item->model_type),
                'model_id' => $item->model_id,
                'url' => CmsGalleryMedia::accessibleUrl($item) ?? $item->getUrl(),
                'thumbnail' => CmsGalleryMedia::displayUrl($item) ?? CmsGalleryMedia::accessibleUrl($item) ?? $item->getUrl(),
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'is_image' => Str::startsWith($item->mime_type, 'image/'),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly uploaded media file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:10240', // 10MB max
            'collection' => 'nullable|string|max:255',
        ]);

        $uploadedFiles = [];
        $collection = $request->input('collection', 'default');

        // Get or create the media library model instance
        $mediaLibrary = MediaLibrary::getInstance();

        // Handle both single file and multiple files
        $files = $request->file('files');
        if (! is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $media = $mediaLibrary->addMedia($file)
                    ->usingName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection($collection);

                $uploadedFiles[] = [
                    'id' => $media->id,
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'thumbnail' => CmsGalleryMedia::displayUrl($media) ?? CmsGalleryMedia::accessibleUrl($media) ?? $media->getUrl(),
                ];
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Media uploaded successfully'),
                'files' => $uploadedFiles,
            ]);
        }

        return redirect()->route('cms.media.index')
            ->with('success', __('Media uploaded successfully'));
    }

    /**
     * Update the specified media resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'collection_name' => 'nullable|string|max:255',
            'custom_properties' => 'nullable|array',
            'file' => 'nullable|file|max:10240', // 10MB max
            'model_type' => 'nullable|string',
            'model_id' => 'nullable|integer',
        ]);

        // Find media by id, and optionally by model_type and model_id
        $query = Media::where('id', $id);

        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->has('model_id') && $request->model_id) {
            $query->where('model_id', $request->model_id);
        }

        $media = $query->firstOrFail();

        // Handle file replacement (similar to CmsSectionController approach)
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Validate file
            if ($file->isValid()) {
                // Get the model that owns this media
                $model = $media->model;
                $oldCollection = $media->collection_name ?? 'default';
                $oldName = $media->name;
                $oldMediaId = $media->id;
                $disk = $media->disk;

                // If no model, use MediaLibrary
                if (! $model) {
                    $model = MediaLibrary::getInstance();
                }

                // Get the old media's directory path (relative path from disk root)
                $oldFullPath = $media->getPath();
                $diskRoot = Storage::disk($disk)->path('');

                // Convert absolute path to relative path
                // Handle both Windows and Unix paths
                $oldRelativePath = str_replace([$diskRoot, '\\'], ['', '/'], $oldFullPath);
                $oldDirectory = dirname($oldRelativePath);

                // Normalize directory path (remove leading/trailing slashes)
                $oldDirectory = trim($oldDirectory, '/');

                $oldFileName = $media->file_name;
                $oldMimeType = $media->mime_type;
                $oldSize = $media->size;

                // Delete only the files from the old directory, not the directory itself
                if (Storage::disk($disk)->exists($oldDirectory)) {
                    $oldFiles = Storage::disk($disk)->allFiles($oldDirectory);
                    foreach ($oldFiles as $oldFile) {
                        Storage::disk($disk)->delete($oldFile);
                    }
                }

                // Store the new file directly in the old directory (no new folder created)
                $newFileName = $file->getClientOriginalName();

                // Ensure old directory exists
                if (! Storage::disk($disk)->exists($oldDirectory)) {
                    Storage::disk($disk)->makeDirectory($oldDirectory);
                }

                // Store the file directly in the old directory (using relative path)
                Storage::disk($disk)->putFileAs($oldDirectory, $file, $newFileName);

                // Get file info
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();

                // Update the existing media record with new file info (keep the old ID)
                DB::table('media')
                    ->where('id', $oldMediaId)
                    ->update([
                        'name' => $validated['name'] ?? $oldName ?? pathinfo($newFileName, PATHINFO_FILENAME),
                        'file_name' => $newFileName,
                        'mime_type' => $mimeType,
                        'size' => $fileSize,
                        'collection_name' => $validated['collection_name'] ?? $oldCollection,
                        'updated_at' => now(),
                    ]);

                // Reload the media object
                $media = Media::find($oldMediaId);

                // Generate conversions if needed (thumbnails, etc.)
                if ($media) {
                    try {
                        // Perform conversions for this media
                        $media->performConversions();
                    } catch (\Exception $e) {
                        // Ignore conversion errors - conversions may be queued
                    }
                }
            }
        }

        // Ensure media object exists
        if (! isset($media)) {
            $media = Media::find($id);
        }

        // Update metadata if provided
        if (isset($validated['name']) && $validated['name'] !== $media->name) {
            $media->name = $validated['name'];
            $media->save();
        }

        if (isset($validated['collection_name']) && $validated['collection_name'] !== $media->collection_name) {
            $media->collection_name = $validated['collection_name'];
            $media->save();
        }

        if (isset($validated['custom_properties'])) {
            $media->custom_properties = array_merge(
                $media->custom_properties ?? [],
                $validated['custom_properties']
            );
            $media->save();
        }

        // Refresh media object one final time to ensure all changes are reflected
        $media->refresh();

        // Clear any cached URL/path data by reloading
        $media = Media::find($media->id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Media updated successfully'),
                'media' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'thumbnail' => $media->getUrl(),
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $this->formatBytes($media->size),
                    'is_image' => Str::startsWith($media->mime_type, 'image/'),
                ],
            ]);
        }

        return redirect()->route('cms.media.index')
            ->with('success', __('Media updated successfully'));
    }

    /**
     * Remove the specified media resource.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $media = Media::findOrFail($id);
            $owner = $media->model;
            $collectionName = $media->collection_name;

            $media->delete();

            if ($owner instanceof CmsSection && $collectionName === 'gallery') {
                $owner->syncPrimaryImageFromGallery();
            }

            return response()->json([
                'success' => true,
                'message' => __('Media deleted successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Error deleting media: ').$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete media files.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:media,id',
        ]);

        $deleted = 0;
        $errors = [];

        foreach ($validated['ids'] as $id) {
            try {
                $media = Media::findOrFail($id);
                $media->delete();
                $deleted++;
            } catch (\Exception $e) {
                $errors[] = "Media ID {$id}: ".$e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => __(':count media files deleted successfully', ['count' => $deleted]),
            'deleted' => $deleted,
            'errors' => $errors,
        ]);
    }

    /**
     * Get collections list for filtering.
     */
    public function getCollections(): JsonResponse
    {
        $collections = Media::select('collection_name')
            ->distinct()
            ->whereNotNull('collection_name')
            ->orderBy('collection_name')
            ->pluck('collection_name');

        return response()->json($collections);
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}

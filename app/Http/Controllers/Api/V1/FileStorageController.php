<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CollegeFile;
use App\Models\University;
use App\Models\UserSignature;
use App\Services\MediaService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileStorageController extends Controller
{
    public function __construct(private MediaService $mediaService)
    {
        $this->middleware('auth:sanctum');
    }

    public function viewMedia(string $id): StreamedResponse
    {
        return $this->mediaService->viewMedia($id);
    }
    public function viewPdf(string $id): StreamedResponse
    {
        return $this->mediaService->viewPdf($id);
    }
    public function showLecture(string $coursePartsId, string $filename): StreamedResponse
    {
        return $this->mediaService->showLecture($coursePartsId, $filename);
    }

    public function showUniversityLogo(int $universityId)
    {
        $university = University::find($universityId);
        abort_if(!$university, 404);

        $logoPath = $university->logo_path;
        abort_if(empty($logoPath), 404);

        $candidates = [];
        if ($logoPath !== '') {
            $candidates[] = $logoPath;
        }

        $normalized = ltrim(preg_replace('#^(private/|public/|storage/)#i', '', $logoPath), '/');
        if ($normalized !== '' && $normalized !== $logoPath) {
            $candidates[] = $normalized;
        }

        foreach ($candidates as $candidate) {
            foreach (['private', 'public'] as $disk) {
                if (Storage::disk($disk)->exists($candidate)) {
                    $mime = Storage::disk($disk)->mimeType($candidate) ?: 'application/octet-stream';
                    return Storage::disk($disk)->response($candidate, basename($candidate), [
                        'Content-Type' => $mime,
                        'Content-Disposition' => 'inline; filename="' . basename($candidate) . '"',
                    ]);
                }
            }
        }

        abort(404);
    }
    public function viewAdvertisementAttachment(string $id): StreamedResponse
    {
        return $this->mediaService->viewAdvertisementAttachment($id);
    }

    public function viewCollegeFile(string $fileId): StreamedResponse
    {
        $file = CollegeFile::find($fileId);
        abort_if(!$file, 404, 'الملف غير موجود');

        $originalPath = $file->path ?? '';
        $candidates = [];

        if ($originalPath !== '') {
            $candidates[] = $originalPath;
        }

        $normalized = ltrim(preg_replace('#^(private/|public/|storage/)#i', '', $originalPath), '/');
        if ($normalized !== '' && $normalized !== $originalPath) {
            $candidates[] = $normalized;
        }

        $found = false;
        $disk = null;
        $filePath = null;

        foreach ($candidates as $candidate) {
            if (Storage::disk('private')->exists($candidate)) {
                $found = true;
                $disk = 'private';
                $filePath = $candidate;
                break;
            }
            if (Storage::disk('public')->exists($candidate)) {
                $found = true;
                $disk = 'public';
                $filePath = $candidate;
                break;
            }
        }

        abort_if(!$found || !$filePath, 404, 'الملف غير موجود على الخادم');

        $mime = Storage::disk($disk)->mimeType($filePath) ?: 'application/octet-stream';
        $headers = ['Content-Type' => $mime];

        if (in_array(strtolower($file->type), ['image', 'pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $headers['Content-Disposition'] = 'inline; filename="' . $file->name . '.' . $file->type . '"';
        } else {
            $headers['Content-Disposition'] = 'attachment; filename="' . $file->name . '.' . $file->type . '"';
        }

        return Storage::disk($disk)->response($filePath, $file->name . '.' . $file->type, $headers);
    }
    public function viewSignature(string $uuidWithExtension): StreamedResponse
    {
        $uuid = pathinfo($uuidWithExtension, PATHINFO_FILENAME);

        $signature = UserSignature::where('signature_uuid', $uuid)->first();
        abort_if(!$signature, 404, 'التوقيع غير موجود');

        $path = $signature->path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'ملف التوقيع غير موجود على الخادم');
        }

        $mime = Storage::disk('local')->mimeType($path) ?: 'image/png';
        $filename = basename($path);

        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ];

        // 6. إرجاع الملف
        return Storage::disk('local')->response($path, $filename, $headers);
    }
}

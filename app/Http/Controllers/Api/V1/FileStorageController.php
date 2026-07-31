<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\University;
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
}

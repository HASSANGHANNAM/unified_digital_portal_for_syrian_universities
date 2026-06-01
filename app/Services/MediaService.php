<?php

namespace App\Services;

use App\DTOs\MediaFileDTO;
use App\Repositories\Contracts\RequestMediaRepositoryInterface;
use App\Services\Traits\TokenDataTrait;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaService
{
    use TokenDataTrait;

    public function __construct(private RequestMediaRepositoryInterface $requestMediaRepository) {}

    public function viewMedia(string $mediaId): StreamedResponse
    {
        $studentId = $this->getStudentId();
        $media = $this->requestMediaRepository->findWithDetails($mediaId);
        abort_if(!$media, 404);
        abort_if(!$media->request || !$media->request->student || (string) $media->request->student_id !== (string) $studentId, 403);
        $originalPath = $media->path ?? '';
        $normalized = ltrim(preg_replace('#^(private/|public/|storage/)#i', '', $originalPath), '/');
        $candidates = [];
        if ($originalPath !== '') {
            $candidates[] = $originalPath;
        }
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
        abort_if(!$found || !$filePath, 404);
        $dto = MediaFileDTO::fromModel($media);
        $mime = Storage::disk($disk)->mimeType($filePath) ?: 'application/octet-stream';
        $headers = ['Content-Type' => $mime];
        if (in_array($dto->type, ['image', 'pdf'], true)) {
            $headers['Content-Disposition'] = 'inline; filename="' . $dto->name . '"';
        } else {
            $headers['Content-Disposition'] = 'attachment; filename="' . $dto->name . '"';
        }
        return Storage::disk($disk)->response($filePath, $dto->name, $headers);
    }
}

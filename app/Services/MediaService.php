<?php

namespace App\Services;

use App\DTOs\MediaFileDTO;
use App\Models\AdvertisementAttachment;
use App\Models\Lecture;
use App\Models\Request;
use App\Repositories\Contracts\RequestMediaRepositoryInterface;
use App\Services\Traits\TokenDataTrait;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class MediaService
{
    use TokenDataTrait;

    public function __construct(private RequestMediaRepositoryInterface $requestMediaRepository) {}

    public function viewMedia(string $mediaId): StreamedResponse
    {
        $studentId = $this->getStudentId();
        $media = $this->requestMediaRepository->findWithDetails($mediaId);
        abort_if(!$media, 404);
        abort_if(!$media->request || !$media->request->student, 403);
        $originalPath = $media->path ?? '';
        $normalized = ltrim((string) preg_replace('#^(private/|public/|storage/)#i', '', $originalPath), '/');
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
    public function extractImageFromBase64(string $base64Image): array
    {
        if (!str_contains($base64Image, ';base64,')) {
            throw new Exception('تنسيق Base64 غير صحيح: يجب أن يحتوي على "data:image/...;base64,"');
        }

        $parts = explode(';base64,', $base64Image);

        if (count($parts) !== 2) {
            throw new Exception('تنسيق Base64 غير صحيح: تأكد من وجود "data:image/png;base64," في بداية النص');
        }

        $header = $parts[0];
        $encodedContent = $parts[1];

        $mimeType = str_replace('data:', '', $header);
        $extension = $this->getExtensionFromMime($mimeType);

        if (!$extension) {
            throw new Exception('نوع الصورة غير مدعوم: ' . $mimeType);
        }

        $binaryContent = base64_decode($encodedContent, true);

        if ($binaryContent === false) {
            throw new Exception('فشل فك ترميز Base64: تأكد من أن النص مشفر بصيغة Base64 صحيحة');
        }

        $sizeInBytes = strlen($binaryContent);
        $sizeInKB = round($sizeInBytes / 1024, 2);

        return [
            'binary' => $binaryContent,              // البيانات الثنائية للصورة
            'extension' => $extension,               // png, jpg, jpeg, gif, svg, webp
            'mime' => $mimeType,                     // image/png, image/jpeg, ...
            'size' => $sizeInKB,                     // الحجم بالكيلوبايت
            'size_bytes' => $sizeInBytes,            // الحجم بالبايت
        ];
    }
    private function getExtensionFromMime(string $mime): ?string
    {
        $map = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/tiff' => 'tiff',
        ];

        return $map[$mime] ?? null;
    }
    public function validateBase64Image(string $base64Image): bool
    {
        try {
            $this->extractImageFromBase64($base64Image);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function generateUniqueFilename(string $extension, string $prefix = 'img'): string
    {
        return $prefix . '_' . now()->format('Ymd_His') . '_' . Str::random(16) . '.' . $extension;
    }
    public function extractUploadedFile(\Illuminate\Http\UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();
        $size = round($file->getSize() / 1024, 2); // بالكيلوبايت

        $binaryContent = file_get_contents($file->getRealPath());
        if ($binaryContent === false) {
            throw new Exception('فشل قراءة محتوى الملف');
        }

        $supported = ['png', 'jpeg', 'jpg', 'gif', 'webp', 'bmp', 'tiff'];
        if (!in_array(strtolower($extension), $supported)) {
            throw new Exception('نوع الملف غير مدعوم: ' . $extension);
        }

        return [
            'binary' => $binaryContent,
            'extension' => $extension,
            'mime' => $mime,
            'size' => $size,
            'size_bytes' => $file->getSize(),
        ];
    }
    public function viewPdf($requestId)
    {
        $request = Request::findOrFail($requestId);

        Gate::authorize('viewPdf', $request);

        if (empty($request->pdf_path)) {
            abort(404, 'لم يتم إنشاء ملف PDF لهذا الطلب بعد.');
        }

        if (!Storage::disk('private')->exists($request->pdf_path)) {
            abort(404, 'ملف PDF غير موجود على الخادم.');
        }

        return Storage::disk('private')->response($request->pdf_path);
    }
    public function uploadLectureFile($file, int $coursePartsId): array
    {
        $extension = $file->getClientOriginalExtension();
        $type = $extension;
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        $stored = Storage::disk('private')->putFileAs(
            'lectures/' . $coursePartsId,
            $file,
            $filename
        );
        if (!$stored) {
            throw new \Exception('فشل في حفظ ملف المحاضرة على الخادم');
        }
        $relativePath = 'lectures/' . $coursePartsId . '/' . $filename;
        return [
            'file_url'  => $relativePath,
            'type'      => $type,
        ];
    }
    public function showLecture(string $coursePartsId, string $filename): StreamedResponse
    {
        $relativePath = "lectures/{$coursePartsId}/{$filename}";
        $lecture = Lecture::where('file_url', $relativePath)->first();
        if (!$lecture) {
            abort(404, 'المحاضرة غير موجودة');
        }
        $fullPath = $relativePath; // <-- التعديل هنا
        if (!Storage::disk('private')->exists($fullPath)) {
            abort(404, 'ملف المحاضرة غير موجود على الخادم');
        }
        $mime = Storage::disk('private')->mimeType($fullPath) ?: 'application/octet-stream';
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => $this->getLectureDisposition($lecture->type, $filename),
        ];
        return Storage::disk('private')->response($fullPath, $filename, $headers);
    }

    private function getLectureDisposition(string $type, string $filename): string
    {
        $inlineTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm'];

        if (in_array(strtolower($type), $inlineTypes)) {
            return 'inline; filename="' . $filename . '"';
        }

        return 'attachment; filename="' . $filename . '"';
    }
    public function viewAdvertisementAttachment(string $attachmentId): StreamedResponse
    {
        $attachment = AdvertisementAttachment::find($attachmentId);
        abort_if(!$attachment, 404, 'المرفق غير موجود');

        $originalPath = $attachment->path;
        if (empty($originalPath)) {
            abort(404, 'المرفق غير موجود');
        }

        $candidates = [$originalPath];
        $normalized = ltrim(preg_replace('#^(private/|public/|storage/)#i', '', $originalPath), '/');
        if ($normalized !== $originalPath) {
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

        $uuid = pathinfo($attachment->path, PATHINFO_FILENAME);
        $fullFilename = $uuid . '.' . $attachment->type;

        $mime = Storage::disk($disk)->mimeType($filePath) ?: 'application/octet-stream';
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => $this->getLectureDisposition($attachment->type, $fullFilename),
        ];

        return Storage::disk($disk)->response($filePath, $fullFilename, $headers);
    }
    public function deleteLectureFile(string $filePath): void
    {
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }
}

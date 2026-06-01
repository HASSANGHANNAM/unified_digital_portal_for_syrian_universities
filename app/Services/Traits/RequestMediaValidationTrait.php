<?php

namespace App\Services\Traits;

use App\Models\RequestType;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

trait RequestMediaValidationTrait
{
    protected function validateRequestTypeMedia(RequestType $requestType, array $uploadedFiles): void
    {
        $required = $this->getRequiredMediaTypes($requestType);
        if (count($uploadedFiles) !== count($required)) {
            throw ValidationException::withMessages(['media' => ['عدد الملفات المرفوعة لا يطابق المتطلبات.']]);
        }
        foreach (array_values($uploadedFiles) as $index => $file) {
            $fileType = $this->getFileType($file);
            $expected = $required[$index] ?? null;
            if ($expected === null) {
                throw ValidationException::withMessages(['media' => ['نوع وسائط غير متوقع.']]);
            }
            if ($expected !== 'other' && $fileType !== $expected) {
                throw ValidationException::withMessages(['media' => ["نوع الملف عند الموضع {$index} غير مطابق للمتوقع."]]);
            }
        }
    }

    protected function getFileType(UploadedFile $file): string
    {
        $mime = strtolower($file->getClientMimeType() ?? '');
        $ext = strtolower($file->getClientOriginalExtension() ?? '');
        if (str_starts_with($mime, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
            return 'image';
        }
        if (str_starts_with($mime, 'video/') || in_array($ext, ['mp4', 'avi', 'mov', 'mkv'])) {
            return 'video';
        }
        if ($mime === 'application/pdf' || $ext === 'pdf') {
            return 'pdf';
        }
        if (str_contains($mime, 'spreadsheet') || in_array($ext, ['xls', 'xlsx', 'csv'])) {
            return 'excel';
        }
        if (str_contains($mime, 'word') || in_array($ext, ['doc', 'docx'])) {
            return 'word';
        }
        return 'other';
    }

    protected function getRequiredMediaTypes(RequestType $requestType): array
    {
        return $requestType->requestTypeMedia->map(fn($m) => $m->type)->toArray();
    }
}

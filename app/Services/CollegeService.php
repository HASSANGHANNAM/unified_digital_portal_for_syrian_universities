<?php

namespace App\Services;

use App\Repositories\Contracts\CollegeRepositoryInterface;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CollegeService
{
    public function __construct(
        private CollegeRepositoryInterface $collegeRepository,
        private MediaService $mediaService,
    ) {}
    public function uploadLogo(int $collegeId, UploadedFile $file): array
    {
        try {
            $college = $this->collegeRepository->findById($collegeId);
            if (!$college) {
                return [
                    'data' => null,
                    'message' => 'الكلية غير موجودة',
                    'code' => 404,
                ];
            }
            $extracted = $this->mediaService->extractUploadedFile($file);
            $uuid = (string) Str::uuid();
            $filename = $uuid . '.' . $extracted['extension'];
            $path = 'private/logos/' . $college->id . '/' . $filename;
            Storage::disk('local')->put($path, $extracted['binary']);
            if (!Storage::disk('local')->exists($path)) {
                return [
                    'data' => null,
                    'message' => 'فشل حفظ ملف الشعار على الخادم',
                    'code' => 500,
                ];
            }
            $this->collegeRepository->update($college, ['logo_path' => $path]);
            return [
                'data' => [
                    'id' => $college->id,
                    'name' => $college->name,
                    'logo_path' => $path,
                    'uuid' => $uuid,
                    'extension' => $extracted['extension'],
                    'size' => $extracted['size'] . ' KB',
                ],
                'message' => 'تم رفع شعار الكلية بنجاح',
                'code' => 200,
            ];
        } catch (Throwable $th) {
            return [
                'data' => null,
                'message' => 'حدث خطأ أثناء رفع الشعار: ' . $th->getMessage(),
                'code' => 500,
            ];
        }
    }
}

<?php

namespace App\Services;

use App\DTOs\LectureDTO;
use App\DTOs\StudentDocumentDTO;
use App\Repositories\Contracts\LectureRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use GuzzleHttp\Psr7\UploadedFile;
use App\Services\MediaService;

class DocumentService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private MediaService $mediaService,
        private LectureRepositoryInterface $lectureRepository

    ) {}

    public function addDocument(array $data, int $studentId): array
    {
        $message = 'إضافة مستند إلى ملف الطالب (لشؤون الطلاب).';
        $code = 200;
        $data = array_merge($data, ["studentId" => $studentId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getDocuments(array $data, int $studentId): array
    {
        $message = 'عرض مستندات ملف الطالب.';
        $code = 200;
        // use App\DTOs\StudentDocumentDTO;
        $data = array_merge($data, ["studentId" => $studentId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }
    public function uploadLecture(array $validated): array
    {
        $file = $validated['file'];
        $uploaded = $this->mediaService->uploadLectureFile($file, $validated['course_parts_id']);

        $dto = new LectureDTO(
            title: $validated['title'],
            file_url: $uploaded['file_url'],
            type: $uploaded['type'],
            upload_date: now(),
            order_index: $validated['order_index'] ?? 0,
            course_parts_id: $validated['course_parts_id'],
        );

        $lecture = $this->lectureRepository->create($dto->toArray());

        return [
            'data'    => ['lecture' => $lecture->toArray()],
            'message' => 'تم رفع المحاضرة بنجاح',
            'code'    => 201,
        ];
    }
    public function getLecturesByCoursePart(int $coursePartsId): array
    {
        $lectures = $this->lectureRepository->findByCoursePartId($coursePartsId);
        $data = $lectures->map(fn($lecture) => LectureDTO::fromModel($lecture)->toResponseArray());
        return [
            'data'    => $data->toArray(),
            'message' => 'تم جلب المحاضرات بنجاح',
            'code'    => 200,
        ];
    }
    public function deleteLecture(int $id): array
    {
        $lecture = $this->lectureRepository->findById($id);
        if (!$lecture) {
            return [
                'data' => [],
                'message' => 'المحاضرة غير موجودة.',
                'code' => 404,
            ];
        }
        $this->mediaService->deleteLectureFile($lecture->file_path);
        $this->lectureRepository->delete($id);

        return [
            'data' => [],
            'message' => 'تم حذف المحاضرة بنجاح.',
            'code' => 200,
        ];
    }

    public function updateLecture(int $id, array $validated): array
    {
        $lecture = $this->lectureRepository->findById($id);
        if (!$lecture) {
            return [
                'data' => [],
                'message' => 'المحاضرة غير موجودة.',
                'code' => 404,
            ];
        }

        $data = [];
        if (isset($validated['title'])) {
            $data['title'] = $validated['title'];
        }
        if (isset($validated['order_index'])) {
            $data['order_index'] = $validated['order_index'];
        }

        if (isset($validated['file'])) {
            $this->mediaService->deleteLectureFile($lecture->file_path);
            $uploaded = $this->mediaService->uploadLectureFile($validated['file'], $lecture->course_parts_id);
            $data['file_url'] = $uploaded['file_url'];
            $data['type'] = $uploaded['type'];
        }

        $this->lectureRepository->update($lecture, $data);

        return [
            'data' => ['lecture' => $lecture->fresh()->toArray()],
            'message' => 'تم تحديث المحاضرة بنجاح.',
            'code' => 200,
        ];
    }
}

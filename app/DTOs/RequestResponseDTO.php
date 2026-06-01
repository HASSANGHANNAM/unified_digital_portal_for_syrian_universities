<?php

namespace App\DTOs;

use App\Models\Request;

class RequestResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $request_type_id,
        public readonly string $reason,
        public readonly ?string $submission_date,
        public readonly string $status,
        public readonly ?string $course_id,
        public readonly array $media
    ) {}

    public static function fromModel(Request $request): self
    {
        $request->loadMissing('media');
        $media = collect($request->media)->map(fn($m) => RequestMediaDTO::fromModel($m)->toArray())->toArray();
        $submission = null;
        if ($request->submission_date instanceof \DateTime) {
            $submission = $request->submission_date->format('Y-m-d H:i:s');
        } elseif (!empty($request->submission_date)) {
            $submission = (string) $request->submission_date;
        }
        return new self(
            (string) $request->id,
            (string) $request->request_type_id,
            (string) $request->reason,
            $submission,
            (string) $request->status,
            $request->course_id ? (string) $request->course_id : null,
            $media
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'request_type_id' => $this->request_type_id,
            'reason' => $this->reason,
            'submission_date' => $this->submission_date,
            'status' => $this->status,
            'course_id' => $this->course_id,
            'media' => $this->media,
        ];
    }
}

<?php

namespace App\DTOs;

use App\Models\Lecture;

class LectureDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $file_url,
        public readonly string $type,
        public readonly string $upload_date,
        public readonly int $order_index,
        public readonly int $course_parts_id,
        public readonly ?int $id = null,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null,
    ) {}
    public function toArray(): array
    {
        return [
            'title'           => $this->title,
            'file_url'        => $this->file_url,
            'type'            => $this->type,
            'upload_date'     => $this->upload_date,
            'order_index'     => $this->order_index,
            'course_parts_id' => $this->course_parts_id,
        ];
    }
    public function toResponseArray(): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'file_url'         => $this->file_url,
            'upload_date'      => $this->upload_date,
            'type'             => $this->type,
            'order_index'      => $this->order_index,
            'course_parts_id'  => $this->course_parts_id,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
    public static function fromModel(Lecture $lecture): self
    {
        return new self(
            title: $lecture->title,
            file_url: $lecture->file_url,
            type: $lecture->type,
            upload_date: $lecture->upload_date,
            order_index: $lecture->order_index,
            course_parts_id: $lecture->course_parts_id,
            id: $lecture->id,
            created_at: $lecture->created_at,
            updated_at: $lecture->updated_at,
        );
    }
}

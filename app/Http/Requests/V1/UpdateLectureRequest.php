<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLectureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'order_index' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'file' => [
                'nullable',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,ppt,pptx,mp4,mp3,zip',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'title.max' => 'عنوان المحاضرة لا يتجاوز 255 حرفاً.',
            'order_index.integer' => 'ترتيب المحاضرة يجب أن يكون عدداً صحيحاً.',
            'order_index.min' => 'ترتيب المحاضرة لا يمكن أن يكون سالباً.',
            'file.max' => 'حجم الملف لا يتجاوز 50 ميجابايت.',
            'file.mimes' => 'نوع الملف غير مسموح به. الأنواع المسموحة: PDF, Word, PowerPoint, MP4, MP3, ZIP.',
        ];
    }
    protected function prepareForValidation(): void
    {
        if ($this->missing('order_index')) {
            $this->merge(['order_index' => 0]);
        }
    }
}

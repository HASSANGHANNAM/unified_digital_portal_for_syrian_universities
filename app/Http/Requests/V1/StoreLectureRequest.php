<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLectureRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_parts_id' => 'required|exists:course_parts,id',
            'title'           => 'required|string|max:255',
            'order_index'     => 'nullable|integer|min:0',
            'file'            => 'required|file|max:51200',
        ];
    }
    public function messages(): array
    {
        return [
            'course_parts_id.required' => 'معرف جزء المقرر مطلوب.',
            'course_parts_id.exists'   => 'جزء المقرر غير موجود في النظام.',
            'title.required'           => 'عنوان المحاضرة مطلوب.',
            'title.max'                => 'عنوان المحاضرة لا يتجاوز 255 حرفاً.',
            'order_index.integer'      => 'ترتيب المحاضرة يجب أن يكون عدداً صحيحاً.',
            'order_index.min'          => 'ترتيب المحاضرة لا يمكن أن يكون سالباً.',
            'file.required'            => 'ملف المحاضرة مطلوب.',
            'file.max'                 => 'حجم الملف لا يتجاوز 50 ميجابايت.',
        ];
    }
}

<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Traits\RequestMediaValidationTrait;
use App\Models\RequestType;
use Illuminate\Validation\ValidationException;

class StoreRequestRequest extends FormRequest
{
    use RequestMediaValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'request_type_id' => 'required|exists:request_types,id',
            'reason' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'media' => 'required|array'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            try {
                $requestTypeId = $this->input('request_type_id');
                $requestType = RequestType::with('requestTypeMedia')->find($requestTypeId);
                $uploaded = $this->file('media') ?? [];
                $this->validateRequestTypeMedia($requestType, $uploaded);
            } catch (ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $validator->errors()->add($field, $message);
                    }
                }
            }
        });
    }
}

<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;

class AddStaffRequest extends AddPersonUserRequest
{
    public function rules(): array
    {
        $parentRules = parent::rules();
        $parentRules['role'] = [
            'required',
            'string',
            'max:255',
            Rule::exists('roles', 'name'), // Spatie roles
        ];
        return $parentRules;
    }

    public function messages(): array
    {
        $parentMessages = parent::messages();
        $parentMessages['role.required'] = 'الصلاحية (الدور) مطلوبة.';
        $parentMessages['role.exists'] = 'الصلاحية المحددة غير موجودة في النظام.';
        return $parentMessages;
    }
}

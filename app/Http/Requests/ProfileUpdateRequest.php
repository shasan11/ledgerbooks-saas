<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Everyone can update their own profile.
        // Branch changes are controlled in rules() (permission-gated).
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
        ];

        // Only allow branch change if user has permission
        if ($user && $user->can('change branch')) {
            $rules['branch_id'] = ['required', 'integer', 'exists:branches,id'];
        }

        return $rules;
    }
}

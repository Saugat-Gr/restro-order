<?php

namespace App\Http\Requests\Staffs;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;


class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('edit-staffs');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',

            'email' => [
                'sometimes',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore(auth()->user()->id),
            ],

            'password' => [
                'nullable',
                'confirmed',
                Rules\Password::defaults(),
            ],

            'avatar' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'status' => [
                'sometimes',
                Rule::in(Status::values()),
            ],
        ];
    }
}

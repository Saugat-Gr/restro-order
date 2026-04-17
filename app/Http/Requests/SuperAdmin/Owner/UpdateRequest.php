<?php

namespace App\Http\Requests\SuperAdmin\Owner;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email' . Rule::unique(User::class)->ignore($this->user()->email),
            'status' => [
                'sometimes',
                Rule::in(Status::values()),
            ],
        ];
    }
}

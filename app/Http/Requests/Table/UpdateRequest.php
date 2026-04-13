<?php

namespace App\Http\Requests\Table;

use App\Enums\TableStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'table_number' => 'sometimes',
            'capacity' => 'sometimes',
            'restaurant_id' => 'sometime|exists:restaurants,id',
            'assigned_staff_id' => 'nullable|exists: users, id',
            'status' => ['required', Rule::in(TableStatus::values())]

        ];
    }
}

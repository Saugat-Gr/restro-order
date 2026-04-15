<?php

namespace App\Http\Requests\Table;

use App\Enums\TableStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('create-table');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_number' => 'required',
            'capacity' => 'required',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'assigned_staff_id' => 'nullable',
            'status' => ['required', Rule::in(TableStatus::values())],

        ];
    }

    public function messages(){
          return[
             'table_number.required' => "Table Identifier is required",
          ];
    }
}

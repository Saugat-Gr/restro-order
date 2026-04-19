<?php

namespace App\Http\Requests\MenuItem;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('create-menu-item');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_name' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpeg,jpg,svg,avif|max:2048',
            'price' => 'required|numeric',
            'status' => 'required|in:' . implode(',', array_column(Status::cases(), 'value')),
            'menu_item_category_id' => 'required|exists:menu_item_categories,id',

        ];
    }

    public function messages()
    {
        return [
            'menu_item_category_id.required' => "A Category is required",
            'description.required' => "A short description of item is required."
        ];
    }
}

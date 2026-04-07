<?php

namespace App\Http\Requests\MenuItem;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'sometimes' means only validate if present in the request
            'item_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|file|mimes:png,jpeg,jpg,svg|max:2048',
            'price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|in:' . implode(',', array_column(Status::cases(), 'value')),
            'menu_item_category_id' => 'sometimes|required|exists:menu_item_categories,id',
            'is_in_stock' => 'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'menu_item_category_id.required' => "A Category is required",
            'description.required' => "A short description of item is required.",
        ];
    }
}
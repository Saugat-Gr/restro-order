<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

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
            'table_id' => ['nullable', 'exists:tables,id'],
            'status' => ['required', 'in:' . implode(',', OrderStatus::values())],
            'items' => ['array'],
            'items.*.id' => ['nullable', 'exists:order_items,id'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}

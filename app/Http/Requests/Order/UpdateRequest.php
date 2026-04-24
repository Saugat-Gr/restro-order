<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatus;
use App\Enums\TransactionMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('update-order');
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
            'status' => ['sometimes', 'in:' . implode(',', OrderStatus::values())],
            'items' => ['array'],
            'items.*.id' => ['nullable', 'exists:order_items,id'],
            'items.*.menu_item_id' => ['sometimes', 'exists:menu_items,id'],
            'items.*.quantity' => ['sometimes', 'integer', 'min:1'],
            'payment_method' => ['sometimes', Rule::in(TransactionMethod::values())]
        ];
    }
}

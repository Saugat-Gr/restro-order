<?php

namespace App\Http\Requests\SuperAdmin\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|size:10',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,gif,svg|max:2048',
            'owner_id' => 'required|exists:users,id'
        ];
    }

    public function messages(){
          return[
             'owner_id' => "Please select an owner.",
             "name" => "Name for the restaurant is requried.",
             "email" => "Email For the Restaurant is required",
             "phone" => "Phone for the Restaurant is required",
             "address" => "Address for the Restaurant is required"
          ];
    }
}

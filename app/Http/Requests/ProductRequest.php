<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['numeric', 'min:0'],
            'sale_price' => ['numeric', 'min:0'],
            'bar_code' => ['required', 'string', 'unique:products,bar_code,'. $this->id],
            'quantity' => ['required', 'integer'],
            'minimum_quantity' => ['nullable', 'integer'],
            'provider_id' => ['required', 'integer'],
            'sub_category_id' => ['required', 'integer'],
            'image' => ['required', 'image'],
            'images' => ['nullable', 'array','image'],
        ];
    }
}
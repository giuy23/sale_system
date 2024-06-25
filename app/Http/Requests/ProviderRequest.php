<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
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
        // 'regex:/^[a-zA-Z0-9]+$/' -> numbers and string
        return [
          'name' => ['required', 'string', 'max:150'],
          'document_number' => ['nullable', 'integer', 'digits:8', 'unique:providers,document_number,'. $this->id],
          'name_company' => ['required', 'string'],
          'cellphone' => ['required', 'integer', 'digits:9'],
          'image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}

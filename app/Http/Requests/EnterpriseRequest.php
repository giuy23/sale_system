<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseRequest extends FormRequest
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
      'name' => ['required', 'string', 'max:150'],
      'name_comercial' => ['required', 'string', 'max:150'],
      'description' => ['required', 'string'],
      'cell_phone' => ['required', 'numeric', 'integer', 'digits:9'],
      'address' => ['required', 'string', 'max:250'],
      'RUC' => ['nullable', 'numeric', 'integer', 'digits:11'],
      // 'image' => ['nullable', 'image', 'max:4096']
    ];
  }
}

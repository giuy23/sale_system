<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
      'full_name' => ['required', 'string'],
      'document_number' => ['required', 'numeric', 'digits:8', 'unique:clients,document_number,' . $this->id],
      'cell_phone' => ['nullable', 'numeric', 'digits:9'],
      'state' => ['nullable', 'boolean']
    ];
  }
}
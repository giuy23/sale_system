<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
      'email' => ['required', 'email', 'unique:users,email,' . $this->id],
      'password' => $this->isMethod("PUT") ? ['sometimes', Rules\Password::defaults()] : ['required', Rules\Password::defaults()],
      'name' => ['required', 'string', 'max:80'],
      'sur_name' => ['required', 'string', 'max:80'],
      'document_number' => ['required', 'numeric', 'digits:8', 'unique:users,document_number,' . $this->id],
      'cell_phone' => ['nullable', 'numeric', 'digits:9'],
      'state' => ['nullable', 'boolean'],
      'role_id' => ['nullable', 'integer'],
      // 'image' => $this->isMethod("PUT") ? ['sometimes', 'image', 'max:4096'] : ['nullable', 'image', 'max:4096'],
      'image' => ['nullable', 'image', 'max:4096']
    ];
  }
}

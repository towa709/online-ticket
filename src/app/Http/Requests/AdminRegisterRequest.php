<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'unique:admins,email'],
      'password' => ['required', 'confirmed', 'min:8'],
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => '名前は必須です。',
      'email.required' => 'メールアドレスは必須です。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
      'email.unique' => 'このメールアドレスは既に登録されています。',
      'password.required' => 'パスワードは必須です。',
      'password.confirmed' => 'パスワード確認が一致しません。',
      'password.min' => 'パスワードは8文字以上で入力してください。',
    ];
  }
}

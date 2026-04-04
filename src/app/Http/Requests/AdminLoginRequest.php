<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest;

class AdminLoginRequest extends LoginRequest
{
  public function rules(): array
  {
    return [
      'email' => ['required', 'email'],
      'password' => ['required', 'min:8'],
    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => 'メールアドレスを入力してください。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
      'password.required' => 'パスワードを入力してください。',
      'password.min' => 'パスワードは8文字以上で入力してください。',
    ];
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // メールアドレス
            'reservation_email' => [
               'required',
                'email',
            ],

            // 予約フォームURL（サフィックス）
            'reservation_suffix' => [
                'nullable',
                'regex:/^[a-zA-Z0-9]+$/',
            ],
        ];
    }

   public function messages(): array
{
    return [
        // メールアドレス
        'reservation_email.required' =>
            'メールアドレスを入力してください。',
        'reservation_email.email' =>
            '正しいメールアドレス形式で入力してください。',

        // 予約フォームURL
        'reservation_suffix.regex' =>
            '半角英数字で入力してください。',
    ];
}
}

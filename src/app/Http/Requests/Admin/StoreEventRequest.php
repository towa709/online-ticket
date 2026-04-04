<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
  /**
   * 認可チェック
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * バリデーションルール
   */
  public function rules(): array
  {
    return [
      'title' => 'required|string|max:255',
      'organization_name' => 'required|string|max:255',
      'reservation_form_url' => 'required|alpha_dash|unique:events,reservation_form_url',
    ];
  }

  /**
   * エラーメッセージ（日本語）
   */
  public function messages(): array
  {
    return [
      'title.required' => '公演名は必須です。',
      'organization_name.required' => '劇団名は必須です。',
      'reservation_form_url.required' => '予約フォームURLを入力してください。',
      'reservation_form_url.alpha_dash' => '予約フォームURLは英数字・ハイフン・アンダースコアのみ使用できます。',
      'reservation_form_url.unique' => 'この予約フォームURLはすでに使用されています。',
    ];
  }
}


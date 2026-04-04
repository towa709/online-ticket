<?php

namespace App\Actions\Fortify;

use App\Models\Admin;
use App\Http\Requests\AdminRegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateAdmin implements CreatesNewUsers
{
  public function create(array $input): Admin
  {
    $formRequest = new AdminRegisterRequest();

    $validator = Validator::make(
      $input,
      $formRequest->rules(),
      $formRequest->messages()
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    return Admin::create([
      'name' => $input['name'],
      'email' => $input['email'],
      'password' => Hash::make($input['password']),
    ]);
  }
}

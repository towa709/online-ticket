<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateOrganizationRequest;


class OrganizationController extends Controller
{
  public function edit()
  {
    $admin = Auth::guard('admin')->user();

    $organization = $admin->organization;

    if (!$organization) {
      $organization = $admin->organization()->create([
        'name' => '未設定の劇団',
      ]);
    }

    $currentEvent = $organization->events()->latest()->first();

    $baseUrl = config('services.reservation.base_url');
    $reservationSuffix = '';

    if ($organization->reservation_url) {
      $reservationSuffix = str_replace(
        $baseUrl,
        '',
        $organization->reservation_url
      );
    }

    return view('admin.organization.edit', compact(
      'organization',
      'currentEvent',
      'reservationSuffix'
    ));
  }

  public function update(UpdateOrganizationRequest $request)
  {
    $organization = Auth::guard('admin')->user()->organization;

    $baseUrl = config('services.reservation.base_url');
    $suffix = $request->reservation_suffix;

    $reservationUrl = $suffix
      ? $baseUrl . ltrim($suffix, '/')
      : null;

    $organization->update([
      'name' => $request->name,
      'reservation_email' => $request->reservation_email,
    ]);

    return redirect()
      ->route('admin.organization.edit')
      ->with('success', '劇団情報を更新しました');
  }
}

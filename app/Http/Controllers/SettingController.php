<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserManagement\Http\Requests\UserProfileUpdateRequest;
use Modules\UserManagement\Http\Requests\UserPasswordUpdateRequest;

class SettingController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$user = \Auth::user()
			->with("profile")
			->first();

		return view("usermanagement::settings.index", compact("user"));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UserProfileUpdateRequest $request, User $user)
	{
		$form = $request->validated();
		$user->name = $form["name"];
		$user->save();

		return back()->with("success", "Profile updated successfully.");
	}

	public function updatePassword(UserPasswordUpdateRequest $request, User $user)
	{
		$validated = $request->validated();
	}
}

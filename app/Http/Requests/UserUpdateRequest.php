<?php

namespace Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\UserManagement\Services\PermissionRegistry;
use Modules\UserManagement\Constants\Permissions;

class UserUpdateRequest extends FormRequest
{
	public function authorize()
	{
		return auth()->check() &&
			(new PermissionRegistry())->userCan(
				auth()->user(),
				Permissions::EDIT_USERS
			);
	}

	public function rules()
	{
		$userId = $this->route("user")->id;
		$tableNames = config("permission.table_names");

		return [
			"name" => "required|string|max:255",
			"email" => "required|email|unique:users,email," . $userId,
			"password" => ["nullable", "confirmed", Password::defaults()],
			"roles" => "required|array",
			"roles.*" => "exists:" . $tableNames["roles"] . ",name",
			"permissions" => "required|array",
			"permissions.*" => "exists:" . $tableNames["permissions"] . ",name",
			"is_active" => "boolean",
		];
	}
}

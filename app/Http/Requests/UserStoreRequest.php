<?php

namespace Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\UserManagement\Services\PermissionRegistry;
use Modules\UserManagement\Constants\Permissions;

class UserStoreRequest extends FormRequest
{
	public function authorize()
	{
		return auth()->check() &&
			(new PermissionRegistry())->userCan(
				auth()->user(),
				Permissions::CREATE_USERS
			);
	}

	public function rules()
	{
		$tableNames = config("permission.table_names");
		return [
			"name" => "required|string|max:255",
			"email" => "required|email|unique:users,email",
			"password" => ["required", Password::defaults()],
			"roles" => "required|array",
			"roles.*" => "exists:" . $tableNames["roles"] . ",name",
			"permissions" => "required|array",
			"permissions.*" => "exists:" . $tableNames["permissions"] . ",name",
			"is_active" => "boolean",
		];
	}

	public function attributes()
	{
		return [
			"name" => "nama",
			"email" => "email",
			"password" => "password",
			"roles" => "peran",
		];
	}
}

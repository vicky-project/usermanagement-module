<?php

namespace Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\UserManagement\Services\PermissionRegistry;
use Modules\UserManagement\Constants\Permissions;

class RoleSynPermissionRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$tableNames = config("permission.table_names");
		return [
			"permissions" => "required|array",
			"permissions.*" => "exists:" . $tableNames["permissions"] . ",name",
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return auth()->check() &&
			(new PermissionRegistry())->userCan(
				auth()->user(),
				Permissions::MANAGE_ROLES
			);
	}
}

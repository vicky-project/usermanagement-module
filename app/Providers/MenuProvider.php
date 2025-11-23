<?php

namespace Modules\UserManagement\Providers;

use Modules\UserManagement\Constants\Permissions;
use Modules\MenuManagement\Interfaces\MenuProviderInterface;

class MenuProvider implements MenuProviderInterface
{
	/**
	 * Get Menu for UserManagement Module.
	 */
	public static function getMenus(): array
	{
		return [
			[
				"id" => "user-management",
				"name" => "User Management",
				"icon" => "people",
				"order" => 1,
				"type" => "group",
				"role" => ["super-admin"],
				"children" => [
					[
						"id" => "users",
						"name" => "Users",
						"route" => "usermanagement.users.index",
						"icon" => "user",
						"order" => 1,
						"role" => "super-admin",
						"permission" => Permissions::VIEW_USERS,
					],
					[
						"id" => "roles",
						"name" => "Roles",
						"route" => "usermanagement.roles.index",
						"icon" => "shield-alt",
						"order" => 2,
						"role" => "super-admin",
						"permission" => Permissions::VIEW_ROLES,
					],
					[
						"id" => "permissions",
						"name" => "Permissions",
						"route" => "usermanagement.permissions.index",
						"icon" => "fingerprint",
						"order" => 3,
						"role" => "super-admin",
						"permission" => Permissions::VIEW_PERMISSIONS,
					],
				],
			],
		];
	}
}

<?php
namespace Modules\UserManagement\Providers\Menu;

use Modules\UserManagement\Constants\Permissions;
use Modules\MenuManagement\Providers\BaseMenuProvider;

class UserManagementMenuProvider extends BaseMenuProvider
{
	protected array $config = [
		"group" => "server",
		"location" => "sidebar",
		"icon" => "fas fa-server",
		"order" => 2,
		"permission" => null,
	];

	public function __construct()
	{
		$moduleName = "UserManagement";
		parent::__construct($moduleName);
	}

	/**
	 * Get all menus
	 */
	public function getMenus(): array
	{
		return [
			$this->item([
				"title" => "User Management",
				"icon" => "fas fa-users",
				"type" => "dropdown",
				"order" => 50,
				"children" => [
					$this->item([
						"title" => "Users",
						"icon" => "fas fa-users-gear",
						"route" => "usermanagement.users.index",
						"order" => 1,
						"permission" => Permissions::VIEW_USERS,
					]),
					$this->item([
						"title" => "Roles",
						"icon" => "fas fa-user-tie",
						"route" => "usermanagement.roles.index",
						"order" => 2,
						"permission" => Permissions::VIEW_ROLES,
					]),
					$this->item([
						"title" => "Permissions",
						"icon" => "fas fa-user-tag",
						"route" => "usermanagement.permissions.index",
						"order" => 3,
						"permission" => Permissions::VIEW_PERMISSIONS,
					]),
				],
			]),
		];
	}
}

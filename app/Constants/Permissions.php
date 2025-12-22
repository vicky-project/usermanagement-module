<?php

namespace Modules\UserManagement\Constants;

class Permissions
{
	// User Permissions
	const VIEW_USERS = "usermanagement.users.view";
	const CREATE_USERS = "usermanagement.users.create";
	const EDIT_USERS = "usermanagement.users.edit";
	const DELETE_USERS = "usermanagement.users.delete";
	const MANAGE_USERS = "usermanagement.users.manage";
	const VIEW_USERS_TRASH = "usermanagement.users.view-trash";

	// Role Permissions
	const VIEW_ROLES = "usermanagement.roles.view";
	const CREATE_ROLES = "usermanagement.roles.create";
	const EDIT_ROLES = "usermanagement.roles.edit";
	const DELETE_ROLES = "usermanagement.roles.delete";
	const MANAGE_ROLES = "usermanagement.roles.manage";

	// Permission Permissions
	const VIEW_PERMISSIONS = "usermanagement.permissions.view";
	const CREATE_PERMISSIONS = "usermanagement.permissions.create";
	const EDIT_PERMISSIONS = "usermanagement.permissions.edit";
	const DELETE_PERMISSIONS = "usermanagement.permissions.delete";

	/**
	 * Get all permissions for this module with their descriptions.
	 */
	public static function all(): array
	{
		return [
			self::VIEW_USERS => "View users",
			self::CREATE_USERS => "Create users",
			self::EDIT_USERS => "Edit users",
			self::DELETE_USERS => "Delete users",
			self::MANAGE_USERS => "Manage users (activate/deactivate)",
			self::VIEW_USERS_TRASH => "View users trash",

			self::VIEW_ROLES => "View roles",
			self::CREATE_ROLES => "Create roles",
			self::EDIT_ROLES => "Edit roles",
			self::DELETE_ROLES => "Delete roles",
			self::MANAGE_ROLES => "Manage roles (sync permissions roles)",

			self::VIEW_PERMISSIONS => "View permissions",
			self::CREATE_PERMISSIONS => "Create permissions",
			self::EDIT_PERMISSIONS => "Edit permissions",
			self::DELETE_PERMISSIONS => "Delete permissions",
		];
	}
}

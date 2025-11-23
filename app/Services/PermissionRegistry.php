<?php

namespace Modules\UserManagement\Services;

use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;

class PermissionRegistry
{
	/**
	 * Register and sync permissions for all modules
	 */
	public function syncAllPermissions(): void
	{
		$activeModules = Module::allEnabled();

		foreach ($activeModules as $module) {
			$this->syncModulePermissions($module->getName());
		}
	}

	/**
	 * Sync permissions for specific module
	 */
	public function syncModulePermissions(string $moduleName): void
	{
		$permissions = $this->getModulePermissions($moduleName);

		foreach ($permissions as $permissionName => $description) {
			Permission::firstOrCreate(
				["name" => $permissionName],
				[
					"description" => $description,
					"module" => $moduleName,
					"guard_name" => "web",
				]
			);
		}
	}

	/**
	 * Get permission configuration from module
	 */
	public function getModulePermissions(string $moduleName): array
	{
		$className = "Modules\\{$moduleName}\\Constants\\Permissions";

		if (!class_exists($className)) {
			return [];
		}

		return $className::all();
	}

	/**
	 * Get all permissions from all active module.
	 */
	public function getAllPermissions(): array
	{
		$allPermissions = [];
		$activeModules = Modules::isEnabled();

		foreach ($activeModules as $module) {
			$modulePermissions = $this->getModulePermissions($module->getName());
			$allPermissions = array_merge($allPermissions, $modulePermissions);
		}

		return $allPermissions;
	}

	/**
	 * Check if user has permission for specific module and resource
	 */
	public function userCan($user, string $permission): bool
	{
		// Fallback if UserManagement is disabled
		if (!Module::isEnabled("UserManagement")) {
			return true;
		}

		try {
			return $user->can($permission);
		} catch (\Exception $e) {
			\Log::warning("Permission check failed: {$e->getMessage()}");
			return true;
		}
	}

	/**
	 * Check if user has any of given permission.
	 */
	public function userCanAny($user, array $permissions): bool
	{
		foreach ($permissions as $permission) {
			if ($this->userCan($user, $permission)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if user has all of given permissions.
	 */
	public function userCanAll($user, array $permissions): bool
	{
		foreach ($permissions as $permission) {
			if (!$this->userCan($user, $permission)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if user has not permission.
	 */
	public function userCanNot($user, string $permission): bool
	{
		return !$this->userCan($user, $permission);
	}
}

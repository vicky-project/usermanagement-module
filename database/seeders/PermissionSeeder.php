<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Modules\UserManagement\Services\PermissionRegistry;

class PermissionSeeder extends Seeder
{
	protected $moduleName = "UserManagement";

	public function run()
	{
		$permissionRegistry = new PermissionRegistry();

		$permissionConfig = $permissionRegistry->syncModulePermissions(
			$this->moduleName
		);
	}
}

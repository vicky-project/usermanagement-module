<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
	public function run()
	{
		$roles = config("usermanagement.roles", []);

		foreach ($roles as $key => $roleData) {
			$role = Role::firstOrCreate([
				"name" => $key,
				"guard_name" => "web",
			]);

			// Sync permissions
			if (count($roleData["permissions"]) > 0) {
				if ($roleData["permissions"][0] === "*") {
					$permissions = Permission::all();
				} else {
					$permissions = Permission::whereIn(
						"name",
						$roleData["permissions"]
					)->get();
				}

				$role->syncPermissions($permissions);
			}
		}
	}
}

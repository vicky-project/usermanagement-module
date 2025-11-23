<?php

namespace Modules\UserManagement\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserManagementDatabaseSeeder extends Seeder
{
	public function run()
	{
		// Reset cached roles and permissions
		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();
		$this->call(PermissionSeeder::class);

		// Reset cached roles and permissions
		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();
		$this->call(RoleSeeder::class);

		// Reset cached roles and permissions
		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();
		$this->call(SuperAdminSeeder::class);

		// Reset cached roles and permissions
		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();
	}
}

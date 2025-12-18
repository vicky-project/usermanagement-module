<?php

namespace Modules\UserManagement\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
	public function run()
	{
		$superAdmin = User::firstOrCreate(
			["email" => "admin@example.com"],
			[
				"name" => "Super Administrator",
				"password" => Hash::make("admin"),
				"is_active" => true,
			]
		);

		$superAdminRole = Role::where("name", "super-admin")->first();
		$allPermission = Permission::all();

		$superAdmin->syncRoles([$superAdminRole->id]);
		$superAdmin->syncPermissions($allPermission);
	}
}

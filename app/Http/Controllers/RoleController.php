<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\UserManagement\Constants\Permissions;
use Modules\UserManagement\Services\PermissionRegistry;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Http\Requests\RoleSynPermissionRequest;

class RoleController extends Controller
{
	protected $permissionRegistry;

	public function __construct(PermissionRegistry $permissionRegistry)
	{
		$this->permissionRegistry = $permissionRegistry;

		$this->middleware(["permission:" . Permissions::VIEW_ROLES])->only([
			"index",
			"show",
		]);
		$this->middleware(["permission:" . Permissions::CREATE_ROLES])->only([
			"create",
			"store",
		]);
		$this->middleware(["permission:" . Permissions::EDIT_ROLES])->only([
			"edit",
			"update",
		]);
		$this->middleware(["permission:" . Permissions::DELETE_ROLES])->only([
			"destroy",
		]);
		$this->middleware(["permission:" . Permissions::MANAGE_ROLES])->only([
			"syncPerms",
		]);
	}

	public function index(Request $request)
	{
		$roles = Role::withCount(["users", "permissions"])
			->latest()
			->paginate(config("usermanagement.pagination.roles"));

		return view("usermanagement::roles.index", compact("roles"));
	}

	public function create()
	{
		$permissions = Permission::all()->groupBy(function ($item) {
			return explode(".", $item->name)[0] ?? "other";
		});

		return view("usermanagement::roles.create", compact("permissions"));
	}

	public function store(Request $request)
	{
		$request->validate([
			"name" => "required|string|unique:roles,name",
			"permissions" => "required|array",
			"permissions.*" => "exists:permissions,id",
		]);

		DB::transaction(function () use ($request) {
			$role = Role::create(["name" => $request->name, "guard_name" => "web"]);
			$role->syncPermissions($request->permissions);
		});

		return redirect()
			->route("usermanagement.roles.index")
			->with("success", "Role berhasil dibuat.");
	}

	public function show(Request $request, Role $role)
	{
		$permissions = Permission::all()->groupBy(function ($item) {
			return explode(".", $item->name)[0] ?? "other";
		});
		$roleHasPermissions = $role->permissions->pluck("id", "name");
		$role->load("permissions", "users");

		if (
			$this->permissionRegistry->userCanNot(
				auth()->user(),
				Permissions::MANAGE_ROLES
			)
		) {
			$request
				->session()
				->now(
					"warning",
					"Readonly mode active when you have not permission to manage this role."
				);
		}

		return view(
			"usermanagement::roles.show",
			compact("role", "permissions", "roleHasPermissions")
		);
	}

	public function edit(Role $role)
	{
		$permissions = Permission::all()->groupBy(function ($item) {
			return explode(".", $item->name)[0] ?? "other";
		});

		$rolePermissions = $role->permissions->pluck("id")->toArray();

		return view(
			"usermanagement::roles.edit",
			compact("role", "permissions", "rolePermissions")
		);
	}

	public function update(Request $request, Role $role)
	{
		$tableNames = config("permission.table_names");
		$request->validate([
			"name" => "required|string|unique:roles,name," . $role->id,
			"permissions" => "required|array",
			"permissions.*" => "exists:" . $tableNames["permissions"] . ",name",
		]);

		DB::transaction(function () use ($request, $role) {
			$role->update(["name" => $request->name]);
			$role->syncPermissions($request->permissions);
		});

		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();

		return redirect()
			->route("usermanagement.roles.index")
			->with("success", "Role berhasil diperbarui.");
	}

	public function destroy(Role $role)
	{
		// Prevent deleting super-admin role
		if ($role->name === "super-admin") {
			return redirect()
				->back()
				->withErrors("Tidak dapat menghapus role Super Admin.");
		}

		$role->delete();

		return redirect()
			->route("usermanagement.roles.index")
			->with("success", "Role berhasil dihapus.");
	}

	public function syncPerms(RoleSynPermissionRequest $request, Role $role)
	{
		$permissions = $request->validated();
		$role->syncPermissions($permissions);

		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();

		return back()->with("success", "Role sync permission successfuly");
	}
}

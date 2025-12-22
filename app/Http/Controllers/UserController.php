<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\UserManagement\Http\Requests\UserStoreRequest;
use Modules\UserManagement\Http\Requests\UserUpdateRequest;
use Modules\UserManagement\Constants\Permissions;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Services\PermissionRegistry;

class UserController extends Controller
{
	protected $permissionRegistry;

	public function __construct(PermissionRegistry $permissionRegistry)
	{
		$this->permissionRegistry = $permissionRegistry;

		$this->middleware(["permission:" . Permissions::VIEW_USERS])->only([
			"index",
			"show",
		]);
		$this->middleware(["permission:" . Permissions::CREATE_USERS])->only([
			"create",
			"store",
		]);
		$this->middleware(["permission:" . Permissions::EDIT_USERS])->only([
			"edit",
			"update",
		]);
		$this->middleware(["permission:" . Permissions::DELETE_USERS])->only([
			"destroy",
		]);
		$this->middleware(["permission:" . Permissions::MANAGE_USERS])->only([
			"toggleActive",
		]);
		$this->middleware(["permission:" . Permissions::VIEW_USERS_TRASH])->only([
			"trash",
		]);
	}

	public function index(Request $request)
	{
		$users = User::with("roles")->get();

		return view("usermanagement::users.index", compact("users"));
	}

	public function create()
	{
		$roles = Role::all();
		return view("usermanagement::users.create", compact("roles"));
	}

	public function store(UserStoreRequest $request)
	{
		DB::transaction(function () use ($request) {
			$user = User::create([
				"name" => $request->name,
				"email" => $request->email,
				"password" => Hash::make($request->password),
				"is_active" => $request->boolean("is_active", true),
			]);

			$user->syncRoles($request->roles);
		});

		return redirect()
			->route("usermanagement.users.index")
			->with("success", "User berhasil dibuat.");
	}

	public function show(User $user)
	{
		$user->load("roles", "permissions");

		return view("usermanagement::users.show", compact("user"));
	}

	public function edit(User $user)
	{
		if (
			$this->permissionRegistry->userCanNot(
				auth()->user(),
				Permissions::EDIT_USERS
			)
		) {
			$request
				->session()
				->now(
					"warning",
					"Readonly mode active when you have not permission to manage this role."
				);
		}

		$roles = Role::all();
		$permissions = Permission::all();
		$userHasRoles = $user->roles->pluck("id", "name");
		$userHasPermissions = $user->permissions->pluck("id", "name");
		return view(
			"usermanagement::users.edit",
			compact(
				"user",
				"roles",
				"userHasRoles",
				"permissions",
				"userHasPermissions"
			)
		);
	}

	public function update(UserUpdateRequest $request, User $user)
	{
		$data = [
			"name" => $request->name,
			"email" => $request->email,
			"is_active" => $request->boolean("is_active", true),
		];

		if ($request->filled("password")) {
			$data["password"] = Hash::make($request->password);
		}

		$user->update($data);
		$user->syncRoles($request->roles);
		$user->syncPermissions($request->permissions);

		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();

		return redirect()
			->route("usermanagement.users.index")
			->with("success", "User berhasil diperbarui.");
	}

	public function destroy(User $user)
	{
		// Prevent deleting yourself
		if ($user->id === auth()->id()) {
			return redirect()
				->back()
				->with("error", "Tidak dapat menghapus akun sendiri.");
		}

		$user->syncRoles([]);
		$user->syncPermissions([]);
		app()[
			\Spatie\Permission\PermissionRegistrar::class
		]->forgetCachedPermissions();
		$user->delete();

		return redirect()
			->route("usermanagement.users.index")
			->with("success", "User berhasil dihapus.");
	}

	public function toggleActive(User $user)
	{
		if ($user->id === auth()->id()) {
			return back()->withErrors("Dilarang menonaktifkan diri sendiri.");
		}

		$user->is_active = !$user->active;
		$user->save();

		return back()->with("success", "Berhasil menonaktifkan user.");
	}

	public function trashed(Request $request)
	{
		$trashed = User::onlyTrashed()->get();

		return view("usermanagement::users.trashed", compact("trashed"));
	}

	public function restore(Request $request, User $user)
	{
		$user->restore();

		return redirect()
			->route("usermanagement.users.index")
			->with("success", "Restore deleted user successfuly.");
	}

	public function delete(Request $request, User $user)
	{
		// Prevent deleting yourself
		if ($user->id === auth()->id()) {
			return redirect()
				->back()
				->with("error", "Tidak dapat menghapus akun sendiri.");
		}

		$user->forceDelete();

		return redirect()
			->route("usermanagement.users.index")
			->with("success", "Permanent deleted user successfuly.");
	}
}

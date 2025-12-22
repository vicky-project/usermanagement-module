<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserController;
use Modules\UserManagement\Http\Controllers\RoleController;
use Modules\UserManagement\Http\Controllers\PermissionController;
use Modules\UserManagement\Http\Controllers\ActivityController;
use Modules\UserManagement\Http\Controllers\SettingController;

Route::middleware(["web", "auth"])
	->prefix("admin")
	->name("usermanagement.")
	->group(function () {
		// Users Routes
		Route::get("users/trashed", [UserController::class, "trashed"])->name(
			"users.trashed"
		);


		Route::post("users/{user}/restore", [
			UserController::class,
			"restore",
		])->name("users.restore");
		Route::post("users/{user}/delete", [UserController::class, "delete"])->name(
			"users.delete"
		);
		Route::post("users/{user}/toggle", [
			UserController::class,
			"toggleActive",
		])->name("users.toggle-active");
		
		Route::resource("users", UserController::class);

		// Roles Routes
		Route::resource("roles", RoleController::class);
		Route::post("roles/{role}/sync-perms", [
			RoleController::class,
			"syncPerms",
		])->name("roles.sync-perms");

		Route::resource("permissions", PermissionController::class);
	});

Route::middleware(["web", "auth"])
	->prefix("profile")
	->name("profile.")
	->group(function () {
		Route::get("profile", [SettingController::class, "index"])->name("index");
		Route::post("profile/{user}", [SettingController::class, "update"])->name(
			"update"
		);
		Route::post("password/{user}", [
			SettingController::class,
			"updatePassword",
		])->name("password.update");
	});

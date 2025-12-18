<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Modules\UserManagement\Constants\Permissions;

class PermissionController extends Controller
{
	public function __construct()
	{
		$this->middleware(["permission:" . Permissions::VIEW_PERMISSIONS])->only([
			"index",
			"show",
		]);
		$this->middleware(["permission:" . Permissions::CREATE_PERMISSIONS])->only([
			"create",
			"store",
		]);
		$this->middleware(["permission:" . Permissions::EDIT_PERMISSIONS])->only([
			"edit",
			"update",
		]);
		$this->middleware(["permission:" . Permissions::DELETE_PERMISSIONS])->only([
			"destroy",
		]);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$permissions = Permission::paginate(10);
		return view("usermanagement::permissions.index", compact("permissions"));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view("usermanagement::create");
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
	}

	/**
	 * Show the specified resource.
	 */
	public function show(Permission $permission)
	{
		return view("usermanagement::permissions.show", compact("permission"));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Permission $permission)
	{
		return view("usermanagement::permissions.edit", compact("permission"));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Permission $permission)
	{
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
	}
}

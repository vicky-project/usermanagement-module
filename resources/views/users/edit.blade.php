@extends('usermanagement::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')
@use('Modules\UserManagement\Services\PermissionRegistry')

@section('title', 'Manage Users')

@section('content')
<div class="card mb-3">
  <div class="card-header text-end">
    <div class="float-start me-auto">
      <a href="{{ route('usermanagement.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-fw fa-arrow-left"></i>
      </a>
    </div>
    <h5 class="card-title">{{$user->name}}</h5><span class="small ms-2">{{$user->email}}</span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('usermanagement.users.update', $user)}}">
      @csrf
      @method('PUT')
      <input type="hidden" name="email" value="{{$user->email}}">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="text" class="form-control" name="password">
      </div>
      <div class="pt-2 mt-4 border-top border-info">
        <div class="row my-2 py-2 justify-content-md-center">
          <div class="col col-lg-2">
            <h5 class="card-title">Roles</h5>
          </div>
          <div class="col-md-auto">
            <div class="form-check form-switch form-switch-lg float-end ms-auto">
              <input class="form-check-input" type="checkbox" id="role-toggle-all" onchange="toggleCheckboxRoles(this)">
              <label class="form-check-label" for="role-toggle-all">Select/Disselect All</label>
            </div>
          </div>
        </div>
        <div class="row mt-2 g-3 align-items-center">
          @foreach($roles as $role)
          <div class="col-auto">
            <div class="form-check form-switch form-switch-xl">
              <input class="form-check-input check-role" type="checkbox" id="role-{{$role->id}}" name="roles[]" value="{{$role->name}}" @checked($userHasRoles->has($role->name)) @disabled((new PermissionRegistry())->userCanNot(auth()->user(), Permissions::EDIT_USERS))>
              <label class="form-check-label" for="role-{{$role->id}}">{{$role->description ?? $role->name}}</label>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="pt-2 mt-4 border-top border-warning">
        <div class="row my-2 py-2 justify-content-md-center">
          <div class="col col-lg-2">
            <h5 class="card-title">Permissions</h5>
          </div>
          <div class="col-md-auto">
            <div class="form-check form-switch form-switch-lg float-end ms-auto">
              <input class="form-check-input" type="checkbox" id="permission-toggle-all" onchange="toggleCheckboxPermissions(this)">
              <label class="form-check-label" for="permission-toggle-all">Select/Disselect All</label>
            </div>
          </div>
        </div>
        <div class="row mt-2 row-cols-1 row-cols-sm-2 row-cols-md-3">
          @foreach($permissions as $permission)
          <div class="col">
            <div class="form-check form-switch form-switch-xl">
              <input class="form-check-input check-permission" type="checkbox" id="permission-{{$permission->id}}" name="permissions[]" value="{{$permission->name}}" @checked($userHasPermissions->has($permission->name)) @disabled((new PermissionRegistry())->userCanNot(auth()->user(), Permissions::EDIT_USERS))>
              <label class="form-check-label" for="permission-{{$permission->id}}">{{$permission->description ?? str($permission->name)->replace('.', ' ')}}</label>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="pt-2 mt-4 border-top border-primary">
        <button class="btn btn-block btn-success" @disabled((new PermissionRegistry())->userCanNot(auth()->user(), Permissions::EDIT_USERS))>
          <i class="fas fa-fw fa-paper-plane"></i>
          Save
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push("scripts")
<script>
  function toggleCheckboxRoles(btn) {
    const checked = btn.checked;
    const allRoles = document.querySelectorAll(".check-role");
    [].forEach.call(allRoles, function(checkbox) {
      checkbox.checked = checked;
    });
  }
  
  function toggleCheckboxPermissions(btn) {
    const checked = btn.checked;
    const allPermissions = document.querySelectorAll(".check-permission");
    [].forEach.call(allPermissions, function(checkbox) {
      checkbox.checked = checked;
    });
  }
  
  function isAnyCheck(classname) {
    const allCheckbox = document.querySelectorAll(classname);
    let isChecked = false;
    for(let x = 0; x < allCheckbox.length; x++) {
      isChecked = allCheckbox[x].checked;
      if(isChecked) break;
    }
    
    return isChecked;
  }
  
  window.addEventListener("DOMContentLoaded", function() {
    if(isAnyCheck(".check-role")) {
      document.getElementById("role-toggle-all").checked = true;
    }
    
    if(isAnyCheck(".check-permission")) {
      document.getElementById("permission-toggle-all").checked = true;
    }
  });
</script>
@endpush
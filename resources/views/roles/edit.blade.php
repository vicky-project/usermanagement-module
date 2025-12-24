@extends('usermanagement::layouts.app')

@section('title', $role->name)

@use('Modules\UserManagement\Services\PermissionRegistry')
@use('Modules\UserManagement\Constants\Permissions')

@section('content')
<div class="card">
  <div class="card-header text-end">
    <div class="float-start me-auto">
      <a href="{{ route('usermanagement.roles.index') }}" class="btn btn-secondary" role="button">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h5 class="card-title">Edit Role</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('usermanagement.roles.update', $role) }}">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="role-name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $role->name }}" id="role-name">
      </div>
      <div class="row border-bottom border-top my-4 py-2 border-info d-flex justify-content-md-center">
        <div class="col col-lg-2">
          <h5 class="card-title">Permissions available.</h5>
        </div>
        <div class="col-md-auto">
          <div class="form-check form-switch form-switch-lg float-end ms-auto">
            <input class="form-check-input" type="checkbox" id="permission-toggle" onchange="toggleCheckboxPermissions(this)">
            <label class="form-check-label" for="permission-toggle">Select/Disselect All</label>
          </div>
        </div>
      </div>
      @forelse($permissions as $name => $permission)
      <div class="row border-bottom border-info pb-2 mb-4">
        <div class="col-md-4 col-lg-2">
          <strong>{{ str($name)->upper() }}</strong>
        </div>
        <div class="col-md-8 col-lg-10 p-2 border border-info rounded">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 my-2">
            @foreach($permission as $perm)
              <div class="col">
                <div class="form-check form-switch form-switch-xl">
                  <input class="form-check-input" type="checkbox" id="permission-{{$perm->id}}" name="permissions[]" value="{{$perm->name}}" @checked(in_array($perm->id, $rolePermissions)) @disabled((new PermissionRegistry())->userCanNot(auth()->user(), Permissions::EDIT_ROLES))>
                  <label class="form-check-label" for="permission-{{$perm->id}}">{{$perm->description ?? str($perm->name)->replace('.', ' ')}}</label>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      @empty
      <span>No permission available.</span>
      @endforelse
      <button type="submit" class="btn btn-block btn-success">
        <i class="fas fa-paper-plane"></i>
          Save
      </button>
    </form>
  </div>
</div>
@endsection

@push("scripts")
<script>
  function toggleCheckboxPermissions(btn) {
    const checked = btn.checked;
    const allPermissions = document.querySelectorAll(".form-check-input");
    [].forEach.call(allPermissions, function(checkbox) {
      checkbox.checked = checked;
    });
  }
  
  function isAnyCheck() {
    const allCheckbox = document.querySelectorAll(".form-check-input");
    let isChecked = false;
    for(let x = 0; x < allCheckbox.length; x++) {
      isChecked = allCheckbox[x].checked;
      if(isChecked) break;
    }
    
    return isChecked;
  }
  
  window.addEventListener("DOMContentLoaded", function() {
    if(isAnyCheck()) {
      document.getElementById("permission-toggle").checked = true;
    }
  });
</script>
@endpush
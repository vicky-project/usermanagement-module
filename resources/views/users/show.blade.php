@extends('usermanagement::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="card mb-4">
  <div class="card-header text-end">
    <div class="float-start me-auto">
      <a href="{{ route('usermanagement.users.index') }}" class="btn btn-secondary">
        <svg class="icon">
          <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-arrow-thick-left') }}"></use>
        </svg>
      </a>
    </div>
    <h5 class="card-title">{{ $user->name }}</h5><span class="small ms-1">{{ $user->email }}</span>
  </div>
  <div class="card-body">
    <ol class="list-group list-group-flush">
      <li class="list-group-item align-items-start">
        <div class="fw-bold">Roles</div>
        @foreach($user->roles as $role)
        <span class="badge rounded-pill text-bg-primary mx-2">{{ $role->name }}</span>
        @endforeach
      </li>
      <li class="list-group-item align-items-start">
        <div class="fw-bold">Permissions</div>
        @foreach($user->permissions as $permission)
        <span class="badge rounded-pill text-bg-warning mx-2">{{ $permission->name }}</span>
        @endforeach
      </li>
    </ol>
  </div>
  <div class="card-footer d-flex justify-content-between">
    <form method="POST" action="{{ route('usermanagement.users.toggle-active',['user' => $user]) }}" id="form-active-toggle">
      @csrf
      <input type="checkbox" class="btn-check" id="btn-user-active" @checked($user->is_active) autocomplete="off" @disabled(!auth()->user()->can(Permissions::MANAGE_USERS))>
      <label class="btn" for="btn-user-active">{{ $user->is_active ? "Active" : "Non Active"}}</label>
    </form>
  </div>
</div>
@endsection

@push("scripts")
<script>
  window.addEventListener("DOMContentLoaded", function() {
    const btnIsActive = document.getElementById("btn-user-active");
    btnIsActive.addEventListener("click", function() {
      document.getElementById("form-active-toggle").submit();
    });
  });
</script>
@endpush
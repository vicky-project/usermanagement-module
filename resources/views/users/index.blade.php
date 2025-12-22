@extends('usermanagement::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')

@section('title', 'Manage Users')

@section('content')
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title">Users</h5><span class="small ms-1">All users available</span>
    @can(Permissions::CREATE_USERS)
    <div class="float-end ms-auto">
      <a href="{{ route('usermanagement.users.create') }}" class="btn btn-success">
        <i class="fas fa-fw fa-plus"></i>
      </a>
    </div>
    @endcan
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              <div class="btn-group" role="group">
                <a href="{{ route('usermanagement.users.show', ["user" => $user]) }}" class="btn btn-outline-primary" title="View">
                  <i class="fas fa-fw fa-eye"></i>
                </a>
                @can(Permissions::MANAGE_USERS)
                <form method="POST" action="{{ route('usermanagement.users.toggle-active', ['user' => $user]) }}" id="form-toggle-active">
                  @csrf
                  <input type="checkbox" class="btn-check" id="btn-user-active" @checked($user->is_active) autocomplete="off">
                  <label class="btn @if($user->is_active) btn-outline-success @else btn-outline-danger @endif" for="btn-user-active">
                    @if($user->is_active)
                    <i class="fas fa-fw fa-toggle-on"></i>
                    @else
                    <i class="fas fa-fw fa-toggle-off"></i>
                    @endif
                  </label>
                </form>
                @endcan
                @can(Permissions::EDIT_USERS)
                <a href="{{ route('usermanagement.users.edit', ["user" => $user]) }}" class="btn btn-outline-secondary" title="Edit">
                  <i class="fas fa-fw fa-edit"></i>
                </a>
                @endcan
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push("scripts")
<script>
  window.addEventListener("DOMContentLoaded", function() {
    const btnIsActive = document.getElementById("btn-user-active");
    if(btnIsActive) {
      btnIsActive.addEventListener("click", function() {
        document.getElementById("form-toggle-active").submit();
    });
    }
  });
</script>
@endpush
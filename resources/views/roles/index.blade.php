@extends('usermanagement::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')

@section('title', 'Manage Roles')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Roles</h5><span class="small ms-1">All roles available</span>
    @can(Permissions::CREATE_ROLES)
    <div class="float-end ms-auto">
      <a href="{{ route('usermanagement.roles.create') }}" class="btn btn-success">
        <i class="fas fa-fw fa-plus"></i>
      </a>
    </div>
    @endcan
  </div>
  <div class="card-body">
    <!-- Roles Table -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th scope="col">Role Name</th>
            <th scope="col">Permissions</th>
            <th>Users</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($roles as $role)
          <tr>
            <td>
              <strong>{{ $role->name }}</strong>
              @if($role->guard_name !== 'web')
                <span class="badge bg-info ms-1">{{ $role->guard_name }}</span>
              @endif
            </td>
            <td scope="col" class="text-center">
              <span class="badge bg-secondary rounded-pill">
                {{ $role->permissions_count ?? $role->permissions->count() }}
              </span>
            </td>
            <td scope="col" class="text-center">
              <span class="badge rounded-pill bg-info">
                {{ $role->users_count ?? $role->users->count() }}
              </span>
            </td>
            <td>
              <div class="btn-group btn-group-sm">
                @can(Permissions::VIEW_ROLES)
                <a href="{{ route('usermanagement.roles.show', ['role' => $role]) }}" class="btn btn-outline-info" title="View">
                  <i class="fas fa-fw fa-eye"></i>
                </a>
                @endcan
                @can(Permissions::EDIT_ROLES)
                  <a href="{{ route('usermanagement.roles.edit', $role) }}" class="btn btn-outline-warning" title="Edit">
                    <i class="fas fa-fw fa-edit"></i>
                  </a>
                  @endcan
                  @can(Permissions::DELETE_ROLES)
                    @if(!$role->is_protected && $role->name !== 'super-admin')
                    <form action="{{ route('usermanagement.roles.destroy', $role) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this role?')" title="Delete">
                        <i class="fas fa-fw fa-trash"></i>
                      </button>
                    </form>
                    @endif
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
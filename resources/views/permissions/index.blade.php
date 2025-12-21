@extends('usermanagement::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')

@section('title', 'Manage Permissions')

@section('content')
<div class="card mb-3">
  <div class="card-header">
    <h5 class="card-title">Permissions</h5>
    <span class="small ms-2">All permission available ({{ $permissions->total() }} items.)</span>
    <div class="float-end ms-auto">
      <a href="{{ route('usermanagement.permissions.create') }}" class="btn btn-success">
        <svg class="icon">
          <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
        </svg>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead>
          <th>#</th>
          <th scope="col">Description</th>
          <th scope="col">Module</th>
          <th>Action</th>
        </thead>
        <tbody>
          @foreach($permissions as $permission)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $permission->descripton ?? str($permission->name)->replace('.', ' ') }}</td>
            <td>{{ $permission->module}}</td>
            <td>
              <div class="btn-group" role="group">
                @can(Permissions::VIEW_PERMISSIONS)
                <a href="{{ route('usermanagement.permissions.show', $permission) }}" class="btn btn-outline-primary" title="View">
                  <svg class="icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-zoom') }}"></use>
                  </svg>
                </a>
                @endcan
                @can(Permissions::EDIT_PERMISSIONS)
                <a href="{{ route('usermanagement.permissions.edit', $permission) }}" class="btn btn-outline-primary" title="Edit">
                  <svg class="icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-pen') }}"></use>
                  </svg>
                </a>
                @endcan
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="pt-2 mt-4 border-top border-warning">
      {{ $permissions->onEachSide(2)->links()}}
    </div>
  </div>
</div>
@endsection
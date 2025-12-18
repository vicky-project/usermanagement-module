@extends('viewmanager::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')

@section('page-title', 'Permission Detail')

@section('content')
<div class="card">
  <div class="card-header text-end">
    <div class="float-start me-auto">
      <a href="{{ route('usermanagement.permissions.index') }}" class="btn btn-secondary">
        <svg class="icon">
          <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-arrow-thick-left') }}"></use>
        </svg>
      </a>
    </div>
    <h5 class="card-title">{{ $permission->description}}</h5><span class="small ms-2">{{ $permission->name}}</span>
  </div>
  <div class="card-body">
    <ul class="list-group list-group-flush">
      <li class="list-group-item">{{$permission->description}}</li>
      <li class="list-group-item">{{$permission->name}}</li>
      <li class="list-group-item">{{$permission->module}}</li>
      <li class="list-group-item">{{ $permission->users->count() ?? 0}} users.</li>
      <li class="list-group-item">{{$permission->roles->count()}} roles.</li>
    </ul>
  </div>
</div>
@endsection
@extends('usermanagement::layouts.app')

@use('Modules\UserManagement\Constants\Permissions')

@section('title', 'Edit Permission')

@section('content')
<div class="card">
  <div class="card-header text-end">
    <div class="float-start me-auto">
      <a href="{{ route('usermanagement.permissions.index') }}" class="btn btn-secondary">
        <i class="fas fa-fw fa-arrow-left"></i>
      </a>
    </div>
    <h5 class="card-title">{{ $permission->description}}</h5><span class="small ms-2">{{$permission->name}}</span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('usermanagement.permissions.update', $permission) }}">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" class="form-control" name="description" value="{{ $permission->description}}">
      </div>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $permission->name}}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Module</label>
        <input type="text" class="form-control" name="modul" value="{{ $permission->module}}">
      </div>
      <div class="mb-3">
        <label class="form-label">Guard Name</label>
        <input type="text" class="form-control" name="guard_name" value="{{ $permission->guard_name}}">
      </div>
      <div class="pt-2 mt-4 border-top border-primary">
        <button type="submit" class="btn btn-block bg-success">
          <i class="fas fa-fw fa-paper-plane"></i>
          Save
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
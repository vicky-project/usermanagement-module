@extends('usermanagement::layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Create User')

@section('page-actions')
    <a href="{{ route('usermanagement.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" 
                      action="{{ isset($user) ? route('usermanagement.users.update', $user) : route('usermanagement.users.store') }}">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Password {{ isset($user) ? '(Leave blank to keep current)' : '*' }}
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" {{ isset($user) ? '' : 'required' }}>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password {{ isset($user) ? '' : '*' }}</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="col-12">
                            <label for="roles" class="form-label">Roles *</label>
                            <select class="form-select @error('roles') is-invalid @enderror" 
                                    id="roles" name="roles[]" multiple required>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" 
                                    {{ in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', isset($user) ? $user->is_active : true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active User</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> 
                                {{ isset($user) ? 'Update User' : 'Create User' }}
                            </button>
                            <a href="{{ route('usermanagement.users.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Tips</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> User Creation Guidelines</h6>
                    <ul class="small mb-0">
                        <li>Password must be at least 8 characters</li>
                        <li>Assign appropriate roles based on user responsibilities</li>
                        <li>Inactive users cannot login to the system</li>
                        <li>Users can have multiple roles with combined permissions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#roles').select2({
            placeholder: 'Select roles...',
            allowClear: true
        });
    });
</script>
@endpush
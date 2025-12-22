@extends('usermanagement::layouts.app')

@section('title', 'Your Profile')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title">{{ $user->name }}</h5><span class="small ms-2">{{ $user->email }}</span>
  </div>
  <div class="card-body">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-profile-tab" data-coreui-toggle="pill" data-coreui-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Profile</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-password-tab" data-coreui-toggle="pill" data-coreui-target="#pills-password" type="button" role="tab" aria-controls="pills-password" aria-selected="false">Password</button>
      </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
        <form method="POST" action="{{ route('profile.update', $user) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{$user->email}}" readonly disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $user->name}}" placeholder="Enter your name..." required>
          </div>
          <div class="pt-2 mt-4 border-top border-primary">
            <button class="btn btn-block bg-success">
              <svg class="icon me-2">
                <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-paper-plane') }}"></use>
              </svg>
              Save
            </button>
          </div>
        </form>
      </div>
      <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab" tabindex="0">
        <form method="POST" action="{{route('profile.password.update', $user)}}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Recent Password</label>
            <input type="password" class="form-control" name="old_password" placeholder="Enter your password..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input class="form-control" name="password" placeholder="Enter new password..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">Repeat Password</label>
            <input class="form-control" name="password_confirmation" placeholder="Repeat new password..." required>
          </div>
          <div class="pt-2 mt-4 border-top border-primary">
            <button class="btn btn-block bg-success">
              <svg class="icon me-2">
                <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-paper-plane') }}"></use>
              </svg>
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
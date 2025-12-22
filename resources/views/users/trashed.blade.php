@extends('usermanagement::layouts.app')

@section('title', 'Deleted Users')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Deleted Users</h5>
    <div class="text-end ms-auto">
      <a href="{{ route('usermanagement.users.index') }}" class="btn btn-secondary" role="button" title="Back">
        <i class="fas fa-fw fa-arrow-left"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Created At</th>
          <th>Deleted At</th>
          <th>Action</th>
        </thead>
        <tbody>
          @forelse($trashed as $trash)
          <tr>
            <td>{{ $trash->id }}</td>
            <td>{{ $trash->name }}</td>
            <td>{{ $trash->email }}</td>
            <td>{{ $trash->created_at->format("d-m-Y H:i:s") }}</td>
            <td>{{ $trash->deleted_at->diffForHumans() }}</td>
            <td>
              <div class="btn-group">
                <form method="POST" action="{{ route('usermanagement.users.restore', ['user' => $trash]) }}">
                  @csrf
                  <button type="submit" class="btn btn-outline-warning" title="Restore"><i class="fas fa-fw fa-sync-alt"></i></button>
                </form>
                <form method="POST" action="{{ route('usermanagement.users.delete', ['user' => $trash]) }}">
                  @csrf
                  <button type="submit" class="btn btn-outline-danger" title="Permanent Delete" onclick="return confirm('Are you sure to delete permanent this user ?');"><i class="fas fa-fw fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center"><em>No any deleted users.</em></td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h5 class="text-white mt-4">Edit User</h5>
  <div class="card">
    <div class="card-body">
  <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
  @csrf
  @method('PUT')
  <div>
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}" style="color: #00328E; border: 1px solid #00328E;" required>
  </div>
  <div>
    <label for="nim" class="form-label">Nim</label>
    <input type="number" name="nim" id="nim" class="form-control" value="{{ $user->nim }}" style="color: #00328E; border: 1px solid #00328E;" required >
  </div>
   <div>
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" style="color: #00328E; border: 1px solid #00328E;" required>
   </div>
   <div>
      <label for="password" class="form-label">Password(Leave blank to keep current password)</label>
      <input type="password" class="form-control" id="password" name="password" style="color: #00328E; border: 1px solid #00328E;" required>
   </div>
    <div>
      <label for="password_confirmation" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" style="color: #00328E; border: 1px solid #00328E;" required>
    </div>

    <div>
      <label for="role" class="form-label">Role</label>
      <select name="role" id="role" class="form-control" style="color: #00328E; border: 1px solid #00328E;" required>
       <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
       <option value="pengguna"{{ $user->role == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
       <option value="pustakawan" {{ $user->role == 'pustakawan' ? 'selected' : '' }}>Pustakawan</option>
      </select>
    </div>
    <button type="submit" class="btn text-white mt-3 w-100" style="background-color: #00328E;">Update User</button>
  </form>
</div>
</div>
</div>
@endsection
  
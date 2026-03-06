@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Edit Admin</h2>
<form action="{{ route('admins.update', $admin->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ $admin->name }}" class="form-control" required></div>
<div class="mb-3"><label>Email</label><input type="email" name="email" value="{{ $admin->email }}" class="form-control" required></div>
<div class="mb-3"><label>Role</label><input type="text" name="role" value="{{ $admin->role }}" class="form-control" required></div>
<button type="submit" class="btn btn-success">Update</button>
</form>
</div>
@endsection

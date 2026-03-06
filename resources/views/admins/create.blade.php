@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Add Admin</h2>
<form action="{{ route('admins.store') }}" method="POST">@csrf
<div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label>Role</label><input type="text" name="role" class="form-control" required></div>
<div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
<div class="mb-3"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
<button type="submit" class="btn btn-success">Save</button>
</form>
</div>
@endsection

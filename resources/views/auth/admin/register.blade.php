@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Admin Registration</h2>
    <form method="POST" action="{{ route('admin.register') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
        <a href="{{ route('admin.login') }}" class="btn btn-link">Login</a>
    </form>
</div>
@endsection


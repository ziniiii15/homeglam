@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Admin Login</h2>
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{ route('admin.register') }}" class="btn btn-link">Register</a>
    </form>
</div>
@endsection

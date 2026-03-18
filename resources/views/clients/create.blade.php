@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Add Client</h2>
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label><input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label><input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label><input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Address</label><input type="text" name="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label><input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection


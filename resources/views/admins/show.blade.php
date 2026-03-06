@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Admin Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $admin->id }}</p>
<p><strong>Name:</strong> {{ $admin->name }}</p>
<p><strong>Email:</strong> {{ $admin->email }}</p>
<p><strong>Role:</strong> {{ $admin->role }}</p>
</div></div>
<a href="{{ route('admins.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

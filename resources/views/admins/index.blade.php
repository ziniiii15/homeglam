@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Admins</h2>
<a href="{{ route('admins.create') }}" class="btn btn-primary mb-3">Add Admin</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered table-striped">
<thead>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
</thead>
<tbody>
@foreach($admins as $a)
<tr>
<td>{{ $a->id }}</td>
<td>{{ $a->name }}</td>
<td>{{ $a->email }}</td>
<td>{{ $a->role }}</td>
<td>
<a href="{{ route('admins.show', $a->id) }}" class="btn btn-info btn-sm">View</a>
<a href="{{ route('admins.edit', $a->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('admins.destroy', $a->id) }}" method="POST" style="display:inline;">
@csrf @method('DELETE')
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</


@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Reviews</h2>
<a href="{{ route('reviews.create') }}" class="btn btn-primary mb-3">Add Review</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered table-striped">
<thead>
<tr>
<th>ID</th><th>Booking</th><th>Client</th><th>Beautician</th><th>Rating</th><th>Comment</th><th>Date</th><th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($reviews as $r)
<tr>
<td>{{ $r->id }}</td>
<td>{{ $r->booking->id }}</td>
<td>{{ $r->client->name }}</td>
<td>{{ $r->beautician->name }}</td>
<td>{{ $r->rating }}</td>
<td>{{ $r->comment }}</td>
<td>{{ $r->date }}</td>
<td>
<a href="{{ route('reviews.show', $r->id) }}" class="btn btn-info btn-sm">View</a>
<a href="{{ route('reviews.edit', $r->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('reviews.destroy', $r->id) }}" method="POST" style="display:inline;">
@csrf @method('DELETE')
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection


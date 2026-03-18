@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Bookings</h2>
<a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Add Booking</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered table-striped">
<thead><tr>
<th>ID</th><th>Client</th><th>Beautician</th><th>Service</th><th>Date</th><th>Time</th><th>Location</th><th>Total Cost</th><th>Status</th><th>Actions</th>
</tr></thead>
<tbody>
@foreach($bookings as $b)
<tr>
<td>{{ $b->id }}</td>
<td>{{ $b->client->name }}</td>
<td>{{ $b->beautician->name }}</td>
<td>{{ $b->service->service_name }}</td>
<td>{{ $b->booking_date }}</td>
<td>{{ $b->booking_time }}</td>
<td>{{ $b->location }}</td>
<td>{{ $b->total_cost }}</td>
<td>{{ ucfirst($b->status) }}</td>
<td>
<a href="{{ route('bookings.show', $b->id) }}" class="btn btn-info btn-sm">View</a>
<a href="{{ route('bookings.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('bookings.destroy', $b->id) }}" method="POST" style="display:inline;">
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


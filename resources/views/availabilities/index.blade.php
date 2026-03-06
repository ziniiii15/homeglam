@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Availabilities</h2>
<a href="{{ route('availabilities.create') }}" class="btn btn-primary mb-3">Add Availability</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered table-striped">
<thead>
<tr>
<th>ID</th><th>Beautician</th><th>Day</th><th>Start Time</th><th>End Time</th><th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($availabilities as $a)
<tr>
<td>{{ $a->id }}</td>
<td>{{ $a->beautician->name }}</td>
<td>{{ $a->day_of_week }}</td>
<td>{{ $a->start_time }}</td>
<td>{{ $a->end_time }}</td>
<td>
<a href="{{ route('availabilities.show', $a->id) }}" class="btn btn-info btn-sm">View</a>
<a href="{{ route('availabilities.edit', $a->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('availabilities.destroy', $a->id) }}" method="POST" style="display:inline;">
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

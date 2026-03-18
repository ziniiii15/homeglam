@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Galleries</h2>
<a href="{{ route('galleries.create') }}" class="btn btn-primary mb-3">Add Gallery</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered table-striped">
<thead>
<tr><th>ID</th><th>Beautician</th><th>Image URL</th><th>Description</th><th>Actions</th></tr>
</thead>
<tbody>
@foreach($galleries as $g)
<tr>
<td>{{ $g->id }}</td>
<td>{{ $g->beautician->name }}</td>
<td>{{ $g->image_url }}</td>
<td>{{ $g->description }}</td>
<td>
<a href="{{ route('galleries.show', $g->id) }}" class="btn btn-info btn-sm">View</a>
<a href="{{ route('galleries.edit', $g->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('galleries.destroy', $g->id) }}" method="POST" style="display:inline;">
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


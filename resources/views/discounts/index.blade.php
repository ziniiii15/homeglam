@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Discounts</h2>
<a href="{{ route('discounts.create') }}" class="btn btn-primary mb-3">Add Discount</a>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-bordered table-striped">
<thead>
<tr>
<th>ID</th><th>Code</th><th>Type</th><th>Value</th><th>Valid From</th><th>Valid To</th><th>Applicable To</th><th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($discounts as $d)
<tr>
<td>{{ $d->id }}</td>
<td>{{ $d->code }}</td>
<td>{{ $d->discount_type }}</td>
<td>{{ $d->value }}</td>
<td>{{ $d->valid_from }}</td>
<td>{{ $d->valid_to }}</td>
<td>{{ $d->applicable_to }}</td>
<td>
<a href="{{ route('discounts.show', $d->id) }}" class="btn btn-info btn-sm">View</a>
<a href="{{ route('discounts.edit', $d->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('discounts.destroy', $d->id) }}" method="POST" style="display:inline;">
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


@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Edit Booking</h2>
<form action="{{ route('bookings.update', $booking->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3">
<label>Client</label>
<select name="client_id" class="form-control" required>
@foreach($clients as $c)
<option value="{{ $c->id }}" {{ $c->id == $booking->client_id ? 'selected' : '' }}>{{ $c->name }}</option>
@endforeach
</select></div>
<div class="mb-3">
<label>Beautician</label>
<select name="beautician_id" class="form-control" required>
@foreach($beauticians as $b)
<option value="{{ $b->id }}" {{ $b->id == $booking->beautician_id ? 'selected' : '' }}>{{ $b->name }}</option>
@endforeach
</select></div>
<div class="mb-3">
<label>Service</label>
<select name="service_id" class="form-control" required>
@foreach($services as $s)
<option value="{{ $s->id }}" {{ $s->id == $booking->service_id ? 'selected' : '' }}>{{ $s->service_name }}</option>
@endforeach
</select></div>
<div class="mb-3"><label>Booking Date</label><input type="date" name="booking_date" value="{{ $booking->booking_date }}" class="form-control" required></div>
<div class="mb-3"><label>Booking Time</label><input type="time" name="booking_time" value="{{ $booking->booking_time }}" class="form-control" required></div>
<div class="mb-3"><label>Location</label><input type="text" name="location" value="{{ $booking->location }}" class="form-control" required></div>
<div class="mb-3"><label>Total Cost</label><input type="number" name="total_cost" value="{{ $booking->total_cost }}" class="form-control" step="0.01" required></div>
<div class="mb-3"><label>Status</label>
<select name="status" class="form-control" required>
<option value="upcoming" {{ $booking->status=='upcoming'?'selected':'' }}>Upcoming</option>
<option value="completed" {{ $booking->status=='completed'?'selected':'' }}>Completed</option>
<option value="canceled" {{ $booking->status=='canceled'?'selected':'' }}>Canceled</option>
</select></div>
<div class="mb-3"><label>Client Notes</label><textarea name="client_notes" class="form-control">{{ $booking->client_notes }}</textarea></div>
<button type="submit" class="btn btn-success">Update</button>
</form>
</div>
@endsection

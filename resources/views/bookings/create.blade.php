@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Add Booking</h2>
<form action="{{ route('bookings.store') }}" method="POST">@csrf
<div class="mb-3">
<label>Client</label>
<select name="client_id" class="form-control" required>
@foreach($clients as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
</select>
</div>
<div class="mb-3">
<label>Beautician</label>
<select name="beautician_id" class="form-control" required>
@foreach($beauticians as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach
</select>
</div>
<div class="mb-3">
<label>Service</label>
<select name="service_id" class="form-control" required>
@foreach($services as $s)<option value="{{ $s->id }}">{{ $s->service_name }}</option>@endforeach
</select>
</div>
<div class="mb-3"><label>Booking Date</label><input type="date" name="booking_date" class="form-control" required></div>
<div class="mb-3"><label>Booking Time</label><input type="time" name="booking_time" class="form-control" required></div>
<div class="mb-3"><label>Location</label><input type="text" name="location" class="form-control" required></div>
<div class="mb-3"><label>Total Cost</label><input type="number" name="total_cost" class="form-control" step="0.01" required></div>
<div class="mb-3"><label>Status</label>
<select name="status" class="form-control" required>
<option value="upcoming">Upcoming</option>
<option value="completed">Completed</option>
<option value="canceled">Canceled</option>
</select></div>
<div class="mb-3"><label>Client Notes</label><textarea name="client_notes" class="form-control"></textarea></div>
<button type="submit" class="btn btn-success">Save</button>
</form>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Booking Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $booking->id }}</p>
<p><strong>Client:</strong> {{ $booking->client->name }}</p>
<p><strong>Beautician:</strong> {{ $booking->beautician->name }}</p>
<p><strong>Service:</strong> {{ $booking->service->service_name }}</p>
<p><strong>Date:</strong> {{ $booking->booking_date }}</p>
<p><strong>Time:</strong> {{ $booking->booking_time }}</p>
<p><strong>Location:</strong> {{ $booking->location }}</p>
<p><strong>Total Cost:</strong> {{ $booking->total_cost }}</p>
<p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
<p><strong>Client Notes:</strong> {{ $booking->client_notes }}</p>
</div></div>
<a href="{{ route('bookings.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

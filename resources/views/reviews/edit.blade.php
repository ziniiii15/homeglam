@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Edit Review</h2>
<form action="{{ route('reviews.update', $review->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3">
<label>Booking</label>
<select name="booking_id" class="form-control" required>
@foreach($bookings as $b)<option value="{{ $b->id }}" {{ $b->id==$review->booking_id?'selected':'' }}>{{ $b->id }}</option>@endforeach
</select></div>
<div class="mb-3">
<label>Client</label>
<select name="client_id" class="form-control" required>
@foreach($clients as $c)<option value="{{ $c->id }}" {{ $c->id==$review->client_id?'selected':'' }}>{{ $c->name }}</option>@endforeach
</select></div>
<div class="mb-3">
<label>Beautician</label>
<select name="beautician_id" class="form-control" required>
@foreach($beauticians as $b)<option value="{{ $b->id }}" {{ $b->id==$review->beautician_id?'selected':'' }}>{{ $b->name }}</option>@endforeach
</select></div>
<div class="mb-3"><label>Rating</label><input type="number" name="rating" value="{{ $review->rating }}" min="1" max="5" class="form-control" required></div>
<div class="mb-3"><label>Comment</label><textarea name="comment" class="form-control" required>{{ $review->comment }}</textarea></div>
<div class="mb-3"><label>Date</label><input type="date" name="date" value="{{ $review->date }}" class="form-control" required></div>
<button type="submit" class="btn btn-success">Update</button>
</form>
</div>
@endsection


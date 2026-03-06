@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Review Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $review->id }}</p>
<p><strong>Booking:</strong> {{ $review->booking->id }}</p>
<p><strong>Client:</strong> {{ $review->client->name }}</p>
<p><strong>Beautician:</strong> {{ $review->beautician->name }}</p>
<p><strong>Rating:</strong> {{ $review->rating }}</p>
<p><strong>Comment:</strong> {{ $review->comment }}</p>
<p><strong>Date:</strong> {{ $review->date }}</p>
</div></div>
<a href="{{ route('reviews.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

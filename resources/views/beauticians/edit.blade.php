@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit Beautician</h2>
    <form action="{{ route('beauticians.update', $beautician->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ $beautician->name }}" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" value="{{ $beautician->email }}" class="form-control" required></div>
        <div class="mb-3"><label>Phone</label><input type="text" name="phone" value="{{ $beautician->phone }}" class="form-control" required></div>
        <div class="mb-3"><label>Address</label><input type="text" name="address" value="{{ $beautician->address }}" class="form-control" required></div>
        <div class="mb-3"><label>Experience</label><input type="text" name="experience" value="{{ $beautician->experience }}" class="form-control" required></div>
        <div class="mb-3"><label>Base Location</label><input type="text" name="base_location" value="{{ $beautician->base_location }}" class="form-control" required></div>
        <div class="mb-3"><label>Booking Fee</label><input type="number" name="booking_fee" value="{{ $beautician->booking_fee }}" class="form-control" step="0.01"></div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection

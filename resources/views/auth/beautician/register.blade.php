@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Beautician Registration</h2>
    <form method="POST" action="{{ route('beautician.register') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
        </div>
        <div class="mb-3">
            <label>Experience</label>
            <input type="text" name="experience" class="form-control" value="{{ old('experience') }}" required>
        </div>
        <div class="mb-3">
            <label>Base Location</label>
            <input type="text" name="base_location" class="form-control" value="{{ old('base_location') }}" required>
        </div>
        <div class="mb-3">
            <label>Booking Fee (optional)</label>
            <input type="number" name="booking_fee" class="form-control" value="{{ old('booking_fee') }}">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
        <a href="{{ route('beautician.login') }}" class="btn btn-link">Login</a>
    </form>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="mt-3 alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection


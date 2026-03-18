@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Availability Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $availability->id }}</p>
<p><strong>Beautician:</strong> {{ $availability->beautician->name }}</p>
<p><strong>Day:</strong> {{ $availability->day_of_week }}</p>
<p><strong>Start Time:</strong> {{ $availability->start_time }}</p>
<p><strong>End Time:</strong> {{ $availability->end_time }}</p>
</div></div>
<a href="{{ route('availabilities.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection


@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Add Availability</h2>
<form action="{{ route('availabilities.store') }}" method="POST">@csrf
<div class="mb-3">
<label>Beautician</label>
<select name="beautician_id" class="form-control" required>
@foreach($beauticians as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach
</select></div>
<div class="mb-3"><label>Day of Week</label><input type="text" name="day_of_week" class="form-control" required></div>
<div class="mb-3"><label>Start Time</label><input type="time" name="start_time" class="form-control" required></div>
<div class="mb-3"><label>End Time</label><input type="time" name="end_time" class="form-control" required></div>
<button type="submit" class="btn btn-success">Save</button>
</form>
</div>
@endsection


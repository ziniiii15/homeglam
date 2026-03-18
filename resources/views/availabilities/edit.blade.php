@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Edit Availability</h2>
<form action="{{ route('availabilities.update', $availability->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3">
<label>Beautician</label>
<select name="beautician_id" class="form-control" required>
@foreach($beauticians as $b)<option value="{{ $b->id }}" {{ $b->id==$availability->beautician_id?'selected':'' }}>{{ $b->name }}</option>@endforeach
</select></div>
<div class="mb-3"><label>Day of Week</label><input type="text" name="day_of_week" value="{{ $availability->day_of_week }}" class="form-control" required></div>
<div class="mb-3"><label>Start Time</label><input type="time" name="start_time" value="{{ $availability->start_time }}" class="form-control" required></div>
<div class="mb-3"><label>End Time</label><input type="time" name="end_time" value="{{ $availability->end_time }}" class="form-control" required></div>
<button type="submit" class="btn btn-success">Update</button>
</form>
</div>
@endsection


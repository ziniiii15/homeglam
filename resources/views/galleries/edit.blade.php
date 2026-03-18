@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Edit Gallery</h2>
<form action="{{ route('galleries.update', $gallery->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3">
<label>Beautician</label>
<select name="beautician_id" class="form-control" required>
@foreach($beauticians as $b)<option value="{{ $b->id }}" {{ $b->id==$gallery->beautician_id?'selected':'' }}>{{ $b->name }}</option>@endforeach
</select></div>
<div class="mb-3"><label>Image URL</label><input type="text" name="image_url" value="{{ $gallery->image_url }}" class="form-control" required></div>
<div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ $gallery->description }}</textarea></div>
<button type="submit" class="btn btn-success">Update</button>
</form>
</div>
@endsection


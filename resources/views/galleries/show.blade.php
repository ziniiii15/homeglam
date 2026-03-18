@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Gallery Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $gallery->id }}</p>
<p><strong>Beautician:</strong> {{ $gallery->beautician->name }}</p>
<p><strong>Image URL:</strong> {{ $gallery->image_url }}</p>
<p><strong>Description:</strong> {{ $gallery->description }}</p>
</div></div>
<a href="{{ route('galleries.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection


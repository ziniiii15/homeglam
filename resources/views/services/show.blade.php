@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Service Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $service->id }}</p>
<p><strong>Name:</strong> {{ $service->service_name }}</p>
<p><strong>Description:</strong> {{ $service->description }}</p>
<p><strong>Base Price:</strong> {{ $service->base_price }}</p>
</div></div>
<a href="{{ route('services.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

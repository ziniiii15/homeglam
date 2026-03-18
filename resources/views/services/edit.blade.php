@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Edit Service</h2>
    <form action="{{ route('services.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Service Name</label>
            <input type="text" name="service_name" class="form-control" value="{{ $service->service_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}" {{ $service->category == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required>{{ $service->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Base Price</label>
            <input type="number" name="base_price" class="form-control" value="{{ $service->base_price }}" step="0.01" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Discount Percentage (%)</label>
            <input type="number" name="discount_percentage" class="form-control" value="{{ $service->discount_percentage ?? 0 }}" min="0" max="100">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection


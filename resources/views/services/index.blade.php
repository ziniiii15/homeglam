@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Services</h2>

    <!-- Add Service Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add Service</button>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Services Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Base Price</th>
                <th>Discount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($services as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->service_name }}</td>
                <td>{{ $s->category }}</td>
                <td>{{ $s->description }}</td>
                <td>{{ number_format($s->base_price, 2) }}</td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editServiceModal{{ $s->id }}">Edit</button>

                    <!-- Delete Form -->
                    <form action="{{ route('services.destroy', $s->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Service Modal -->
            <div class="modal fade" id="editServiceModal{{ $s->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('services.update', $s->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">Edit Service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Service Name</label>
                                    <input type="text" name="service_name" class="form-control" value="{{ $s->service_name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}" {{ $s->category == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control">{{ $s->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="base_price" class="form-control" value="{{ $s->base_price }}" min="0" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Service</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @endforeach
        </tbody>
    </table>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="service_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="base_price" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount_percentage" class="form-control" min="0" max="100" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


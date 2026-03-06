@extends('layouts.app')

@section('content')
<!-- Add Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .admin-wrapper {
        min-height: 100vh;
        padding-bottom: 3rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        backdrop-filter: blur(4px);
    }

    .table-custom thead th {
        background-color: transparent;
        border-bottom: 2px solid #eee;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #555;
    }
</style>

<div class="admin-wrapper">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small fw-bold mb-2 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
                <h2 class="fw-bold text-dark mb-0">Manage Beauticians</h2>
            </div>
            <a href="{{ route('beauticians.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Add New Beautician
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="card glass-card">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Professional</th>
                                <th>Contact</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($beauticians as $b)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('beauticians.show', $b->id) }}" class="text-decoration-none">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3 bg-warning-subtle text-warning fw-bold">
                                                {{ substr($b->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark hover-primary">{{ $b->name }}</div>
                                                <small class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i>{{ $b->base_location }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $b->email }}</span>
                                        <span class="text-muted small"><i class="bi bi-telephone me-2"></i>{{ $b->phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge bg-light text-dark border"><i class="bi bi-people me-1"></i>{{ $b->max_bookings }} Max Bookings</span>
                                        @if($b->is_verified)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($b->banned_until && $b->banned_until->isFuture())
                                        <span class="badge bg-danger">Banned until {{ $b->banned_until->format('M d') }}</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if($b->banned_until && $b->banned_until->isFuture())
                                            <form action="{{ route('admin.beauticians.unban', $b->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill" onclick="return confirm('Lift ban for this beautician?')">
                                                    <i class="bi bi-check-circle me-1"></i>Unban
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#banModal{{ $b->id }}">
                                                <i class="bi bi-slash-circle me-1"></i>Ban
                                            </button>
                                        @endif

                                        <a href="{{ route('beauticians.edit', $b->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('beauticians.destroy', $b->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Are you sure you want to delete this beautician?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Ban Modal -->
                                    <div class="modal fade text-start" id="banModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-danger">Ban {{ $b->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.beauticians.ban', $b->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="days" class="form-label fw-bold">Ban Duration (Days)</label>
                                                            <input type="number" class="form-control" name="days" min="1" required placeholder="Enter number of days">
                                                            <div class="form-text">The beautician will be restricted from accessing the dashboard.</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4">Impose Ban</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">No beauticians found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

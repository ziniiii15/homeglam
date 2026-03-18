@extends('layouts.app')

@section('content')
<!-- Add Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-pink: #ff8fb3;
        --neon-pink: #ff5c9a;
        --dark-bg: #fff7fb;
        --card-bg: #ffffff;
        --glass-bg: rgba(255, 255, 255, 0.96);
        --text-main: #212529;
        --text-muted: #6c757d;
        --border-color: rgba(255, 143, 179, 0.25);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--dark-bg);
        color: var(--text-main);
    }

    .admin-wrapper {
        min-height: 100vh;
        padding-bottom: 1.5rem;
        background: radial-gradient(circle at top right, #ffe5f0, #ffffff 60%);
    }
    
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid rgba(255, 143, 179, 0.2);
        border-radius: 20px;
        box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(8px);
    }

    .section-title {
        color: var(--text-main);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .table-custom thead th {
        background-color: #fff7fb;
        border-bottom: 1px solid var(--border-color);
        color: var(--primary-pink);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .table-custom tbody td {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        color: var(--text-main);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    
    .badge-pending { background-color: rgba(255, 193, 7, 0.12); color: #b08900; border: 1px solid rgba(255, 193, 7, 0.3); }
    .badge-resolved { background-color: rgba(25, 135, 84, 0.12); color: #0f5132; border: 1px solid rgba(25, 135, 84, 0.3); }
    .badge-reviewed { background-color: rgba(220, 53, 69, 0.12); color: #842029; border: 1px solid rgba(220, 53, 69, 0.3); }

    .table-hover tbody tr:hover td {
        background-color: rgba(255, 143, 179, 0.08);
        color: var(--text-main);
    }

    .text-primary-pink {
        color: var(--primary-pink) !important;
    }
</style>

<div class="admin-wrapper p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-primary-pink me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                Appeal Management
            </h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="card glass-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Beautician</th>
                                <th>Reason</th>
                                <th>Proof</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appeals as $appeal)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 text-white bg-gradient-pink rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ substr($appeal->beautician->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $appeal->beautician->name }}</div>
                                            <small class="text-muted">{{ $appeal->beautician->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td style="max-width: 300px;">
                                    <div class="text-truncate" title="{{ $appeal->reason }}">
                                        {{ Str::limit($appeal->reason, 100) }}
                                    </div>
                                    @if(strlen($appeal->reason) > 100)
                                        <button class="btn btn-link btn-sm p-0 text-primary-pink" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $appeal->id }}">Read More</button>
                                    @endif
                                </td>
                                <td>
                                    @if($appeal->proof_image)
                                        <a href="{{ asset('view-asset/' . $appeal->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="bi bi-image me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted small">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appeal->status == 'pending')
                                        <span class="badge badge-pending rounded-pill px-3">Pending</span>
                                    @elseif($appeal->status == 'resolved')
                                        <span class="badge badge-resolved rounded-pill px-3">Approved</span>
                                    @else
                                        <span class="badge badge-reviewed rounded-pill px-3">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $appeal->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    @if($appeal->status == 'pending')
                                        <div class="btn-group">
                                            <form action="{{ route('admin.appeals.resolve', $appeal->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this appeal? The beautician will be unbanned.')">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success me-2">
                                                    <i class="bi bi-check-lg me-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.appeals.resolve', $appeal->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this appeal? The ban will remain.')">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-lg me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">Processed</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Reason Modal -->
                            <div class="modal fade" id="reasonModal{{ $appeal->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-white border border-light-subtle text-dark" style="border-radius: 16px;">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Appeal Reason</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{ $appeal->reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    No appeals found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


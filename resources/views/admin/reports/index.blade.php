@extends('layouts.app')

@section('content')
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

    /* Table Styles */
    .table-custom {
        margin-bottom: 0;
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
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    .table-hover tbody tr:hover td {
        background-color: rgba(255, 143, 179, 0.08);
        color: var(--text-main);
    }

    .text-primary-pink {
        color: var(--primary-pink) !important;
    }
</style>

<div class="admin-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-5 d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-chevron-left me-2 text-primary-pink"></i>
                <span class="text-dark">Back to Dashboard</span>
            </a>
        </div>
    </nav>

    <div class="container mt-2">
        <h2 class="section-title mb-4">Client Reports Management</h2>

        <div class="card glass-card">
            <div class="card-header bg-transparent border-bottom border-secondary pt-3 px-3 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-flag me-2 text-primary-pink"></i>All Reports</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Booking Info</th>
                                <th>Reason</th>
                                <th>Proof</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td class="ps-3 text-muted">#{{ $report->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold">{{ $report->booking->service->service_name }}</span>
                                        <small class="text-muted">Client: {{ $report->client->name }}</small>
                                        <small class="text-muted">Beautician: {{ $report->beautician->name }}</small>
                                        <small class="text-muted">Date: {{ $report->created_at->format('M d, Y') }}</small>
                                    </div>
                                </td>
                                <td style="max-width: 300px;">
                                    <p class="mb-0 text-muted text-truncate" title="{{ $report->reason }}">{{ $report->reason }}</p>
                                </td>
                                <td>
                                    @if($report->proof_image)
                                        <a href="{{ asset('storage/' . $report->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">View Image</a>
                                    @else
                                        <span class="text-muted small">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($report->status == 'reviewed')
                                        <span class="badge bg-info text-white">Reviewed</span>
                                    @elseif($report->status == 'resolved')
                                        <span class="badge bg-success">Resolved</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <form action="{{ route('admin.reports.update_status', $report) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm border-secondary" onchange="this.form.submit()">
                                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            </select>
                                        </form>

                                        @if($report->beautician)
                                            @if($report->beautician->banned_until && $report->beautician->banned_until->isFuture())
                                                <form action="{{ route('admin.beauticians.unban', $report->beautician->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill w-100" onclick="return confirm('Lift ban for {{ $report->beautician->name }}?')">
                                                        <i class="bi bi-check-circle me-1"></i>Unban
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" 
                                                    class="btn btn-sm btn-outline-danger rounded-pill w-100" 
                                                    data-beautician-name="{{ $report->beautician->name }}"
                                                    data-ban-url="{{ route('admin.beauticians.ban', $report->beautician->id) }}"
                                                    onclick="openBanModal(this.getAttribute('data-beautician-name'), this.getAttribute('data-ban-url'))">
                                                    <i class="bi bi-slash-circle me-1"></i>Ban
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No reports found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generic Ban Modal -->
<div class="modal fade" id="banBeauticianModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg bg-white text-dark" style="border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">Ban <span id="banModalBeauticianName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="banBeauticianForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Ban Duration (Days)</label>
                        <input type="number" name="days" class="form-control" min="1" required placeholder="Enter number of days">
                        <div class="form-text text-muted">The beautician will be restricted from accessing the platform.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Impose Ban</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openBanModal(name, url) {
        document.getElementById('banModalBeauticianName').textContent = name;
        document.getElementById('banBeauticianForm').action = url;
        var myModal = new bootstrap.Modal(document.getElementById('banBeauticianModal'));
        myModal.show();
    }
</script>
@endsection

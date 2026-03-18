@extends('layouts.app')

@section('content')
@php
    $subscriptionQrPath = null;
    if (file_exists(base_path('storage/uploads/admin/subscription_qr.png'))) {
        $subscriptionQrPath = asset('view-upload/admin/subscription_qr.png');
    }
    $transactions = $transactions ?? collect();
@endphp

<style>
.subscription-bg {
    background: linear-gradient(135deg, #fff5f7 0%, #fdf2ff 40%, #ffffff 100%);
    min-height: 100vh;
}
.subscription-card {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(214, 51, 132, 0.15);
    border: 1px solid rgba(214, 51, 132, 0.15);
}
.subscription-header {
    border-bottom: 1px solid rgba(214, 51, 132, 0.15);
    padding: 1.25rem 1.5rem;
}
.subscription-title {
    font-weight: 800;
    letter-spacing: -0.5px;
}
.subscription-section-title {
    font-weight: 700;
}
.subscription-table th {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
</style>

<div class="container-fluid subscription-bg py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="bg-white subscription-card mb-4">
                <div class="subscription-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width:44px;height:44px;">
                            <i class="bi bi-arrow-clockwise"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 subscription-title text-dark">Beautician Subscription Renewal</h4>
                            <small class="text-muted">Keep your HomeGlam subscription active to continue receiving bookings.</small>
                        </div>
                    </div>
                </div>
                <div class="p-4 p-md-5">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-5">
                            <h6 class="subscription-section-title mb-2">Scan QR to Pay</h6>
                            <p class="text-muted small mb-3">Use your preferred payment app to scan this QR and pay your subscription renewal.</p>

                            @if($subscriptionQrPath)
                                <div class="border rounded-4 p-3 mb-2 bg-light">
                                    <img src="{{ $subscriptionQrPath }}" alt="Admin Subscription QR" class="img-fluid" style="max-height: 240px; object-fit: contain;">
                                </div>
                                <small class="text-muted d-block">After payment, upload a clear screenshot of your receipt.</small>
                            @else
                                <div class="border rounded-4 p-4 mb-2 text-muted text-center bg-light">
                                    <i class="bi bi-qr-code fs-1 d-block mb-2 text-warning"></i>
                                    <span>Subscription QR not available yet. Please contact the admin.</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h6 class="subscription-section-title mb-3">Upload Renewal Proof</h6>
                            @if(session('success'))
                                <div class="alert alert-success py-2 mb-3">
                                    {{ session('success') }}
                                </div>
                            @else
                                <div class="alert alert-info py-2 mb-3">
                                    Please upload a clear screenshot or photo of your latest subscription payment.
                                </div>
                            @endif

                            <form action="{{ route('beautician.subscription_proof') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="subscription_proof" class="form-label">Payment proof image</label>
                                    <input type="file" name="subscription_proof" id="subscription_proof" class="form-control" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    Submit Renewal Proof
                                </button>
                            </form>

                            <p class="small text-muted mb-3">
                                Once your renewal payment proof is reviewed, the admin will verify it and you will be able to access your dashboard again.
                            </p>
                            <form action="{{ route('beautician.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary rounded-pill px-4">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white subscription-card">
                <div class="subscription-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 subscription-section-title text-dark">
                        <i class="bi bi-receipt-cutoff me-2 text-warning"></i>
                        Beautician Subscription Transactions
                    </h5>
                </div>
                <div class="p-3 p-md-4">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle subscription-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $tx)
                                    <tr>
                                        <td class="text-muted">{{ $tx->type ?? 'Renewal' }}</td>
                                        <td class="fw-semibold text-dark">₱{{ number_format($tx->amount ?? 0, 2) }}</td>
                                        <td class="text-muted">{{ $tx->created_at ?? '-' }}</td>
                                        <td>
                                            <span class="badge rounded-pill px-3 bg-primary-subtle text-primary">
                                                {{ ucfirst($tx->status ?? 'paid') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            No subscription renewal transactions recorded yet.
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
</div>
@endsection


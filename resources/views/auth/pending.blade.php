@extends('layouts.app')

@section('content')
@php
    $subscriptionQrPath = null;
    if (file_exists(base_path('storage/uploads/admin/subscription_qr.png'))) {
        $subscriptionQrPath = asset('view-upload/admin/subscription_qr.png');
    }

    $hasProof = false;
    $proofUrl = null;

    $beautician = auth()->guard('beautician')->user();
    if ($beautician) {
        $beauticianId = $beautician->id;
        $pattern = base_path('storage/uploads/subscription_proofs/beautician_' . $beauticianId . '.*');
        $files = glob($pattern);
        if ($files && count($files) > 0) {
            $hasProof = true;
            $relative = str_replace(base_path('storage/uploads'), '', $files[0]);
            $relative = ltrim($relative, '\\/');
            $proofUrl = asset('view-upload/' . $relative);
        }
    }

    $isExpired = false;
    if ($beautician && $beautician->subscription_expires_at && $beautician->subscription_expires_at->isPast()) {
        $isExpired = true;
    }
@endphp

<style>
.pending-subscription-bg {
    background: linear-gradient(135deg, #fff5f7 0%, #fdf2ff 40%, #ffffff 100%);
    min-height: 100vh;
}
.pending-card {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(214, 51, 132, 0.15);
    border: 1px solid rgba(214, 51, 132, 0.12);
}
.pending-header {
    border-bottom: 1px solid rgba(214, 51, 132, 0.15);
    padding: 1.25rem 1.5rem;
}
.pending-title {
    font-weight: 800;
    letter-spacing: -0.5px;
}
.pending-section-title {
    font-weight: 700;
}
</style>

<div class="container-fluid pending-subscription-bg py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="bg-white pending-card">
                <div class="pending-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width:44px;height:44px;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 pending-title text-dark">Verification Pending</h4>
                            <small class="text-muted">Complete your subscription payment so the admin can verify your account.</small>
                        </div>
                    </div>
                </div>
                <div class="p-4 p-md-5">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-5">
                            <h6 class="pending-section-title mb-2">Admin Subscription QR</h6>
                            <p class="text-muted small mb-3">Thank you for registering as a beautician. Please pay the subscription fee using the QR below.</p>

                            @if($subscriptionQrPath)
                                <div class="border rounded-4 p-3 mb-2 bg-light">
                                    <img src="{{ $subscriptionQrPath }}" alt="Admin Subscription QR" class="img-fluid" style="max-height: 240px; object-fit: contain;">
                                </div>
                                <small class="text-muted d-block">Scan this QR to pay your subscription.</small>
                            @else
                                <div class="border rounded-4 p-4 mb-2 text-muted text-center bg-light">
                                    <i class="bi bi-qr-code fs-1 d-block mb-2 text-warning"></i>
                                    <span>Admin has not uploaded a subscription QR yet.</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7" id="subscription-renew-section">
                            <h6 class="pending-section-title mb-3">Upload proof of payment</h6>
                            @if(!$isExpired && $hasProof && $proofUrl)
                                <div class="alert alert-success py-2 mb-3">
                                    Subscription payment proof submitted. Waiting for admin verification.
                                </div>
                                <div class="mb-3">
                                    <div class="small text-muted mb-1">Your submitted proof:</div>
                                    <img src="{{ $proofUrl }}" alt="Subscription Proof" class="img-fluid rounded border" style="max-height: 260px; object-fit: contain;">
                                </div>
                            @else
                                <div class="alert alert-info py-2 mb-3">
                                    No subscription payment proof found yet. Please upload a clear screenshot or photo of your payment.
                                </div>
                            @endif

                            <form action="{{ route('beautician.subscription_proof') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="subscription_proof" class="form-label">Payment proof image</label>
                                    <input type="file" name="subscription_proof" id="subscription_proof" class="form-control" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    Submit Proof of Payment
                                </button>
                            </form>

                            <p class="small text-muted mb-3">
                                Once your payment proof is reviewed, the admin will verify your account and you will be able to access your dashboard.
                            </p>
                            <form action="{{ route('beautician.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary rounded-pill px-4">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($isExpired)
<div class="modal fade" id="subscriptionExpiredModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Subscription Expired</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    Your beautician subscription has expired. To continue using your dashboard and accepting bookings, you need to renew your subscription.
                </p>
                <p class="mb-0 text-muted">
                    You can renew now using the subscription QR below, or logout and renew later.
                </p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('beautician.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        Logout
                    </button>
                </form>
                <button type="button" class="btn btn-primary" id="subscriptionExpiredRenewBtn">
                    Renew Subscription
                </button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('subscriptionExpiredModal');
    if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        return;
    }
    var modal = new bootstrap.Modal(modalEl);
    modal.show();

    var renewBtn = document.getElementById('subscriptionExpiredRenewBtn');
    if (renewBtn) {
        renewBtn.addEventListener('click', function () {
            modal.hide();
            var target = document.getElementById('subscription-renew-section');
            if (target && target.scrollIntoView) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});
</script>
@endif
@endsection


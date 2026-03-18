@extends('layouts.app')

@section('content')
<!-- Add Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-danger">BANNED</h1>
        <h2 class="mb-4">Access Restricted</h2>
        <p class="lead mb-4">
            Your account has been suspended by the administrator.<br>
            @if(auth()->guard('beautician')->user()->banned_until)
                You are banned until: <strong>{{ auth()->guard('beautician')->user()->banned_until->format('F j, Y, g:i a') }}</strong>
            @else
                <strong>Your account is suspended indefinitely.</strong>
            @endif
        </p>
        
        @if(session('success'))
            <div class="alert alert-success d-inline-block mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @else
            <div class="alert alert-warning d-inline-block cursor-pointer" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#appealModal">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span class="text-decoration-underline">Please contact support if you believe this is a mistake.</span>
            </div>
        @endif

        <div class="mt-5">
            <form action="{{ route('beautician.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</div>

<!-- Appeal Modal -->
<div class="modal fade text-start" id="appealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Appeal Suspension</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('beautician.appeal') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        If you believe your account was suspended in error, please explain why below. You may also attach an image as proof.
                    </p>
                    <div class="mb-3">
                        <label for="reason" class="form-label fw-bold">Reason for Appeal</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required placeholder="Explain why your ban should be lifted..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proof_image" class="form-label fw-bold">Proof Image (Optional)</label>
                        <input type="file" class="form-control" id="proof_image" name="proof_image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Appeal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


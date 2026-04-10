@extends('layouts.app')

@section('content')
@php
    $beautician = auth()->guard('beautician')->user();
    $reason = $beautician ? $beautician->rejection_reason : null;
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.denied-bg {
    background: linear-gradient(135deg, #fff5f7 0%, #fdf2ff 40%, #ffffff 100%);
    min-height: 100vh;
}
.denied-card {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(220, 53, 69, 0.12);
    border: 1px solid rgba(220, 53, 69, 0.12);
}
.denied-header {
    border-bottom: 1px solid rgba(220, 53, 69, 0.12);
    padding: 1.25rem 1.5rem;
}
.denied-title {
    font-weight: 800;
    letter-spacing: -0.5px;
}
</style>

<div class="container-fluid denied-bg py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="bg-white denied-card">
                <div class="denied-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width:44px;height:44px;">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 denied-title text-dark">Verification Denied</h4>
                            <small class="text-muted">Your account verification was denied by the admin.</small>
                        </div>
                    </div>
                </div>
                <div class="p-4 p-md-5">
                    <div class="alert alert-danger border-0 rounded-4 mb-4" style="background: rgba(220, 53, 69, 0.08);">
                        <div class="fw-bold mb-1 text-dark">You cannot access the beautician dashboard right now.</div>
                        <div class="text-muted small">
                            @if($reason)
                                Reason: {{ $reason }}
                            @else
                                Please contact support or try registering again with correct documents.
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <form action="{{ route('beautician.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary rounded-pill px-4">Logout</button>
                        </form>
                        <a href="{{ route('welcome') }}" class="btn btn-primary rounded-pill px-4">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

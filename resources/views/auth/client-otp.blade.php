@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 py-md-5" style="background: var(--bg);">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-6">
            <div class="bg-white rounded-4 shadow-sm border" style="border-color: var(--border-color) !important;">
                <div class="d-flex flex-wrap align-items-center justify-content-between px-4 px-md-5 pt-4 pt-md-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:44px;height:44px;background: var(--primary-light); color: var(--primary);">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 text-dark">Verify Your Account</h4>
                            <small class="text-muted">A verification code will be sent via SMS.</small>
                        </div>
                    </div>
                </div>
                <div class="p-4 p-md-5">
                    @if($maskedEmail || $maskedPhone)
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Confirm your account</div>
                            @if($maskedEmail)
                                <div>Email: {{ $maskedEmail }}</div>
                            @endif
                            @if($maskedPhone)
                                <div>Phone: {{ $maskedPhone }}</div>
                            @endif
                        </div>
                    @endif

                    @if(session('otp_sent'))
                        <div class="alert alert-success mb-3">
                            A verification code has been sent to your phone.
                        </div>
                    @else
                        <form method="POST" action="{{ route('client.password.otp.send') }}" class="mb-4">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                Send SMS Code
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('client.password.otp.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Enter 6-digit code</label>
                            <input type="text" name="code" class="form-control" required maxlength="6">
                            @error('code')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Verify Code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('content')
<div class="container-fluid pending-subscription-bg py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-6">
            <div class="bg-white pending-card">
                <div class="pending-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width:44px;height:44px;">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 pending-title text-dark">Forgot Password</h4>
                            <small class="text-muted">Find your account using your email or phone number.</small>
                        </div>
                    </div>
                </div>
                <div class="p-4 p-md-5">
                    @if(session('recovery_notice'))
                        <div class="alert alert-info mb-3">
                            {{ session('recovery_notice') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('client.password.forgot.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email or Phone Number</label>
                            <input type="text" name="identifier" class="form-control" required>
                            @error('identifier')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Next
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


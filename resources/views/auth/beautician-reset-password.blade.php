@extends('layouts.app')

@section('content')
<div class="container-fluid pending-subscription-bg py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-6">
            <div class="bg-white pending-card">
                <div class="pending-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width:44px;height:44px;">
                            <i class="bi bi-key"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 pending-title text-dark">Change Password</h4>
                            <small class="text-muted">Create a strong new password for your account.</small>
                        </div>
                    </div>
                </div>
                <div class="p-4 p-md-5">
                    <form method="POST" action="{{ route('beautician.password.reset') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Update Password
                        </button>
                        <div class="small text-muted mt-3">
                            Minimum 8 characters, with at least 1 uppercase letter, 1 number, and 1 special character.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


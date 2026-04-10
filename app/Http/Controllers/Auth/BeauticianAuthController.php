<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Beautician;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BeauticianAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.beautician-login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.beautician-forgot-password');
    }

    protected function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (Str::startsWith($digits, '09')) {
            return '+63' . substr($digits, 1);
        }
        if (Str::startsWith($digits, '9') && strlen($digits) === 10) {
            return '+63' . $digits;
        }
        if (Str::startsWith($digits, '63')) {
            return '+' . $digits;
        }
        if (Str::startsWith($digits, '8') && strlen($digits) === 9) {
            return '+63' . $digits;
        }
        return '+63' . substr($digits, -10);
    }

    public function handleForgotPassword(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $data['identifier'];

        $query = Beautician::query();
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $query->where('email', $identifier);
        } else {
            $normalized = $this->normalizePhone($identifier);
            $query->where('phone', $normalized);
        }

        $beautician = $query->first();

        if (!$beautician) {
            return redirect()
                ->route('beautician.password.otp.form')
                ->with('recovery_notice', 'If the account exists, a verification code will be sent.');
        }

        $maskedEmail = null;
        if ($beautician->email) {
            $parts = explode('@', $beautician->email);
            $name = $parts[0];
            $domain = $parts[1] ?? '';
            $maskedEmail = substr($name, 0, 1) . str_repeat('*', max(strlen($name) - 2, 1)) . substr($name, -1) . '@' . $domain;
        }

        $maskedPhone = null;
        if ($beautician->phone) {
            $digits = preg_replace('/\D+/', '', $beautician->phone);
            $lastThree = substr($digits, -3);
            $maskedPhone = '09******' . $lastThree;
        }

        $request->session()->put('password_recovery_beautician_id', $beautician->id);
        $request->session()->put('password_recovery_masked_email', $maskedEmail);
        $request->session()->put('password_recovery_masked_phone', $maskedPhone);

        return redirect()->route('beautician.password.otp.form');
    }

    public function showOtpForm(Request $request)
    {
        $beauticianId = $request->session()->get('password_recovery_beautician_id');
        $maskedEmail = $request->session()->get('password_recovery_masked_email');
        $maskedPhone = $request->session()->get('password_recovery_masked_phone');

        return view('auth.beautician-otp', compact('beauticianId', 'maskedEmail', 'maskedPhone'));
    }

    protected function sendOtpSms(string $phone, string $message): void
    {
        try {
            Http::post('https://www.iprogsms.com/api/v1/sms_messages', [
                'api_token' => 'e61f155b1ac0fd65c6d3d134c60848edb2575260',
                'phone_number' => $phone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP SMS Error (Beautician): ' . $e->getMessage());
        }
    }

    public function sendOtp(Request $request)
    {
        $beauticianId = $request->session()->get('password_recovery_beautician_id');
        if (!$beauticianId) {
            return redirect()->route('beautician.password.forgot');
        }

        $beautician = Beautician::find($beauticianId);
        if (!$beautician) {
            return redirect()
                ->route('beautician.password.forgot')
                ->with('recovery_notice', 'If the account exists, a verification code will be sent.');
        }

        PasswordOtp::where('user_id', $beautician->id)
            ->where('user_type', 'beautician')
            ->update(['used' => true]);

        $code = random_int(100000, 999999);
        $otp = PasswordOtp::create([
            'user_id' => $beautician->id,
            'user_type' => 'beautician',
            'code_hash' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
            'used' => false,
        ]);

        if ($beautician->phone) {
            $this->sendOtpSms($beautician->phone, 'Your password reset code is: ' . $code);
        }

        $request->session()->put('password_reset_otp_id', $otp->id);

        return redirect()->route('beautician.password.otp.form')->with('otp_sent', true);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|digits:6',
        ]);

        $otpId = $request->session()->get('password_reset_otp_id');
        $beauticianId = $request->session()->get('password_recovery_beautician_id');

        if (!$otpId || !$beauticianId) {
            return redirect()->route('beautician.password.forgot');
        }

        $otp = PasswordOtp::where('id', $otpId)
            ->where('user_id', $beauticianId)
            ->where('user_type', 'beautician')
            ->first();

        if (!$otp || $otp->used || $otp->expires_at->isPast() || $otp->attempts >= 3) {
            return back()->withErrors(['code' => 'The verification code is invalid or has expired.']);
        }

        $otp->attempts = $otp->attempts + 1;

        if (!Hash::check($data['code'], $otp->code_hash)) {
            $remaining = max(0, 3 - $otp->attempts);
            $otp->save();
            return back()->withErrors(['code' => 'Incorrect code. Attempts remaining: ' . $remaining]);
        }

        $otp->used = true;
        $otp->save();

        $request->session()->put('password_reset_verified_beautician', $beauticianId);

        return redirect()->route('beautician.password.reset.form');
    }

    public function showResetForm(Request $request)
    {
        $beauticianId = $request->session()->get('password_reset_verified_beautician');
        if (!$beauticianId) {
            return redirect()->route('beautician.password.forgot');
        }
        return view('auth.beautician-reset-password');
    }

    public function resetPassword(Request $request)
    {
        $beauticianId = $request->session()->get('password_reset_verified_beautician');
        if (!$beauticianId) {
            return redirect()->route('beautician.password.forgot');
        }

        $data = $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ]);

        $beautician = Beautician::findOrFail($beauticianId);
        $beautician->password = Hash::make($data['password']);
        $beautician->save();

        $request->session()->forget('password_reset_verified_beautician');
        $request->session()->forget('password_reset_otp_id');
        $request->session()->forget('password_recovery_beautician_id');
        $request->session()->forget('password_recovery_masked_email');
        $request->session()->forget('password_recovery_masked_phone');

        return redirect()->route('beautician.login')->with('status', 'Password successfully updated.');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $emailKey = 'beautician_login_attempts_' . strtolower($request->input('email'));
        $attempts = (int) $request->session()->get($emailKey, 0);

        if (Auth::guard('beautician')->attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->forget($emailKey);
            
            $beautician = Auth::guard('beautician')->user();
            $expired = false;
            $hasSubscriptionProof = false;

            if ($beautician) {
                $directory = base_path('storage/uploads/subscription_proofs');
                $pattern = $directory . DIRECTORY_SEPARATOR . 'beautician_' . $beautician->id . '.*';
                $files = glob($pattern);
                if ($files && count($files) > 0) {
                    $hasSubscriptionProof = true;
                }
            }

            if ($beautician && $beautician->subscription_expires_at && $beautician->subscription_expires_at->isPast()) {
                $expired = true;
                if ($beautician->is_verified) {
                    Beautician::whereKey($beautician->id)->update(['is_verified' => false]);
                    $beautician->is_verified = false;
                }
            }
            if ($beautician && ($beautician->rejection_reason || $beautician->verification_status === 'denied')) {
                return app('redirect')->route('beautician.denied');
            }
            if (!$beautician->is_verified && (!$expired || $hasSubscriptionProof)) {
                return app('redirect')->route('beautician.pending');
            }
            
            return app('redirect')->route('beautician.dashboard');
        }

        $attempts++;
        $request->session()->put($emailKey, $attempts);

        $error = 'Invalid credentials.';
        if ($attempts >= 3) {
            $error = 'Too many failed attempts. You may reset your password.';
        }

        return back()
            ->withErrors(['email' => $error])
            ->with('too_many_attempts', $attempts >= 3)
            ->withInput();
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.beautician-register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:beauticians,email',
            'phone_number' => 'required|string|size:10|regex:/^[9]\d{9}$/',
            'address' => 'required|string|max:255',
            'verification_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'password' => 'required|confirmed|min:6',
        ]);

        $documentPath = null;
        if ($request->hasFile('verification_document')) {
             $directory = base_path('storage/uploads/verification_documents');
             if (!is_dir($directory)) {
                 mkdir($directory, 0755, true);
             }
             $file = $request->file('verification_document');
             $filename = time() . '_' . $file->getClientOriginalName();
             $file->move($directory, $filename);
             $documentPath = 'view-upload/verification_documents/' . $filename;
        }

        $beautician = Beautician::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => '+63' . $request->phone_number,
            'address'       => $request->address,
            'base_location' => $request->address,
            'verification_document' => $documentPath,
            'is_verified'   => false,
            'password'      => Hash::make($request->password),
        ]);

        Auth::guard('beautician')->login($beautician);
        $request->session()->regenerate();

        // FIXED
        return app('redirect')->route('beautician.pending');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('beautician')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // FIXED
        return app('redirect')->route('welcome');
    }
}


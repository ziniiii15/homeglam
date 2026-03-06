<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.client-login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            return back()->withErrors(['email' => 'Client not found']);
        }

        $emailKey = 'client_login_attempts_' . strtolower($request->input('email'));
        $attempts = (int) $request->session()->get($emailKey, 0);

        if (Hash::check($request->password, $client->password)) {
            Auth::guard('client')->login($client);
            $request->session()->regenerate();
            $request->session()->forget($emailKey);

            // FIXED: use app('redirect') instead of helper redirect()
            return app('redirect')->route('client.dashboard');
        }

        $attempts++;
        $request->session()->put($emailKey, $attempts);

        $error = 'Invalid password';
        if ($attempts >= 3) {
            $error = 'Too many failed attempts. You may reset your password.';
        }

        return back()
            ->withErrors(['password' => $error])
            ->with('client_too_many_attempts', $attempts >= 3)
            ->withInput();
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

    public function showForgotPasswordForm()
    {
        return view('auth.client-forgot-password');
    }

    public function handleForgotPassword(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $data['identifier'];

        $query = Client::query();
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $query->where('email', $identifier);
        } else {
            $normalized = $this->normalizePhone($identifier);
            $query->where('phone', $normalized);
        }

        $client = $query->first();

        if (!$client) {
            return redirect()
                ->route('client.password.otp.form')
                ->with('recovery_notice', 'If the account exists, a verification code will be sent.');
        }

        $maskedEmail = null;
        if ($client->email) {
            $parts = explode('@', $client->email);
            $name = $parts[0];
            $domain = $parts[1] ?? '';
            $maskedEmail = substr($name, 0, 1) . str_repeat('*', max(strlen($name) - 2, 1)) . substr($name, -1) . '@' . $domain;
        }

        $maskedPhone = null;
        if ($client->phone) {
            $digits = preg_replace('/\D+/', '', $client->phone);
            $lastThree = substr($digits, -3);
            $maskedPhone = '09******' . $lastThree;
        }

        $request->session()->put('client_password_recovery_id', $client->id);
        $request->session()->put('client_password_recovery_masked_email', $maskedEmail);
        $request->session()->put('client_password_recovery_masked_phone', $maskedPhone);

        return redirect()->route('client.password.otp.form');
    }

    public function showOtpForm(Request $request)
    {
        $clientId = $request->session()->get('client_password_recovery_id');
        $maskedEmail = $request->session()->get('client_password_recovery_masked_email');
        $maskedPhone = $request->session()->get('client_password_recovery_masked_phone');

        return view('auth.client-otp', compact('clientId', 'maskedEmail', 'maskedPhone'));
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
            \Illuminate\Support\Facades\Log::error('OTP SMS Error (Client): ' . $e->getMessage());
        }
    }

    public function sendOtp(Request $request)
    {
        $clientId = $request->session()->get('client_password_recovery_id');
        if (!$clientId) {
            return redirect()->route('client.password.forgot');
        }

        $client = Client::find($clientId);
        if (!$client) {
            return redirect()
                ->route('client.password.forgot')
                ->with('recovery_notice', 'If the account exists, a verification code will be sent.');
        }

        PasswordOtp::where('user_id', $client->id)
            ->where('user_type', 'client')
            ->update(['used' => true]);

        $code = random_int(100000, 999999);
        $otp = PasswordOtp::create([
            'user_id' => $client->id,
            'user_type' => 'client',
            'code_hash' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
            'used' => false,
        ]);

        if ($client->phone) {
            $this->sendOtpSms($client->phone, 'Your password reset code is: ' . $code);
        }

        $request->session()->put('client_password_reset_otp_id', $otp->id);

        return redirect()->route('client.password.otp.form')->with('otp_sent', true);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|digits:6',
        ]);

        $otpId = $request->session()->get('client_password_reset_otp_id');
        $clientId = $request->session()->get('client_password_recovery_id');

        if (!$otpId || !$clientId) {
            return redirect()->route('client.password.forgot');
        }

        $otp = PasswordOtp::where('id', $otpId)
            ->where('user_id', $clientId)
            ->where('user_type', 'client')
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

        $request->session()->put('client_password_reset_verified', $clientId);

        return redirect()->route('client.password.reset.form');
    }

    public function showResetForm(Request $request)
    {
        $clientId = $request->session()->get('client_password_reset_verified');
        if (!$clientId) {
            return redirect()->route('client.password.forgot');
        }
        return view('auth.client-reset-password');
    }

    public function resetPassword(Request $request)
    {
        $clientId = $request->session()->get('client_password_reset_verified');
        if (!$clientId) {
            return redirect()->route('client.password.forgot');
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

        $client = Client::findOrFail($clientId);
        $client->password = Hash::make($data['password']);
        $client->save();

        $request->session()->forget('client_password_reset_verified');
        $request->session()->forget('client_password_reset_otp_id');
        $request->session()->forget('client_password_recovery_id');
        $request->session()->forget('client_password_recovery_masked_email');
        $request->session()->forget('client_password_recovery_masked_phone');

        return redirect()->route('client.login')->with('status', 'Password successfully updated.');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.client-register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone_number' => 'required|string|size:10|regex:/^[9]\d{9}$/',
            'address' => 'required|string|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        $client = Client::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => '+63' . $request->phone_number,
            'address'  => $request->address,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('client')->login($client);

        // FIXED: use app('redirect') instead of helper redirect()
        return app('redirect')->route('client.dashboard');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // FIXED: use app('redirect') instead of helper redirect()
        return app('redirect')->route('welcome');
    }
}

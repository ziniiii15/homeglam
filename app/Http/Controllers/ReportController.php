<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // Client submits a report
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'reason' => 'required|string|max:1000',
            'proof_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Ensure the booking belongs to the authenticated client
        if ($booking->client_id !== Auth::guard('client')->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $path = null;
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('reports', 'public');
        }

        Report::create([
            'booking_id' => $booking->id,
            'client_id' => Auth::guard('client')->id(),
            'beautician_id' => $booking->beautician_id,
            'reason' => $request->reason,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        // Notify Beautician (Reported)
        try {
            $beautician = \App\Models\Beautician::find($booking->beautician_id);
            if ($beautician && $beautician->phone) {
                $clientName = Auth::guard('client')->user()->name;
                \Illuminate\Support\Facades\Http::post('https://www.iprogsms.com/api/v1/sms_messages', [
                    'api_token' => 'e61f155b1ac0fd65c6d3d134c60848edb2575260',
                    'phone_number' => $beautician->phone,
                    'message' => "Alert: You have been reported by {$clientName} for Booking #{$booking->id}. Reason: {$request->reason}.",
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS Error: ' . $e->getMessage());
        }

        return back()->with('report_submitted', true)->with('success', 'Report submitted successfully. We will review it shortly.');
    }

    // Admin views all reports
    public function index()
    {
        $reports = Report::with(['client', 'beautician', 'booking.service'])->latest()->get();
        return view('admin.reports.index', compact('reports'));
    }

    // Admin updates report status
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('success', 'Report status updated.');
    }
}

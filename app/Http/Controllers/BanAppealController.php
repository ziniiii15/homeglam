<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BanAppeal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BanAppealController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
            'proof_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $path = null;
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('appeals', 'public');
        }

        BanAppeal::create([
            'beautician_id' => Auth::guard('beautician')->id(),
            'reason' => $request->reason,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your appeal has been submitted successfully.');
    }
}


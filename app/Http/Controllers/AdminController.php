<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Beautician;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\BanAppeal;
use App\Models\Category;

class AdminController extends Controller
{
    public function dashboard()
    {
        $clients = Client::all();
        $beauticians = Beautician::with('serviceList')->get();
        $admins = Admin::all(); // Add admins for list
        $pendingAppealsCount = BanAppeal::where('status', 'pending')->count(); // Add count for dashboard
        
        $pendingVerifications = Beautician::where('is_verified', false)->get();
        $pendingVerificationsCount = $pendingVerifications->count();

        $categories = Category::all();
        $subscriptionTransactions = collect();

        return view('dashboard.admin', compact(
            'clients',
            'beauticians',
            'admins',
            'pendingAppealsCount',
            'pendingVerifications',
            'pendingVerificationsCount',
            'categories',
            'subscriptionTransactions'
        ));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'circle', // Default icon
        ]);

        return back()->with('success', 'Category added successfully.');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }


    public function verifyBeautician(Beautician $beautician)
    {
        $directory = base_path('storage/uploads/subscription_proofs');
        $pattern = $directory . DIRECTORY_SEPARATOR . 'beautician_' . $beautician->id . '.*';
        $files = glob($pattern);

        if (!$files || count($files) === 0) {
            return back()->with('error', 'Beautician has not submitted subscription payment proof yet.');
        }

        $beautician->update([
            'is_verified' => true,
            'subscription_expires_at' => now()->addMonth(),
        ]);
        return back()->with('success', 'Beautician verified successfully.');
    }

    public function indexAppeals()
    {
        $appeals = BanAppeal::with('beautician')->latest()->get();
        return view('admin.appeals.index', compact('appeals'));
    }

    public function resolveAppeal(Request $request, BanAppeal $appeal)
    {
        // If approved, unban the beautician
        if ($request->action === 'approve') {
            $appeal->status = 'resolved';
            $appeal->beautician->banned_until = null;
            $appeal->beautician->save();
            $message = 'Appeal approved. Beautician has been unbanned.';
        } else {
            // Reject appeal
            $appeal->status = 'reviewed';
            $message = 'Appeal rejected. Ban remains.';
        }
        
        $appeal->save();

        return back()->with('success', $message);
    }

    public function index()
    {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:admins',
            'password' => 'required|min:6|confirmed',
            'role'=>'required',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('admins.index')->with('success','Admin created successfully.');
    }

    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:admins,email,'.$admin->id,
            'role'=>'required',
        ]);

        $admin->update($request->except('password'));
        return redirect()->route('admins.index')->with('success','Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success','Admin deleted successfully.');
    }

    // Ban Beautician
    public function banBeautician(Request $request, Beautician $beautician)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $beautician->banned_until = now()->addDays((int) $request->days);
        $beautician->save();

        return back()->with('success', 'Beautician has been banned for ' . $request->days . ' days.');
    }

    // Unban Beautician
    public function unbanBeautician(Beautician $beautician)
    {
        $beautician->banned_until = null;
        $beautician->save();

        return back()->with('success', 'Beautician ban has been lifted.');
    }

    public function testBeauticianSubscription(Beautician $beautician)
    {
        $beautician->update([
            'subscription_expires_at' => now()->subMinute(),
        ]);

        return back()->with('success', 'Beautician subscription expiry test applied.');
    }

    public function updateSubscriptionQr(Request $request)
    {
        $request->validate([
            'subscription_qr' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('subscription_qr')) {
            $directory = base_path('storage/uploads/admin');

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('subscription_qr');
            $targetPath = $directory . DIRECTORY_SEPARATOR . 'subscription_qr.png';
            if (is_file($targetPath)) {
                @unlink($targetPath);
            }
            $file->move($directory, 'subscription_qr.png');
        }

        return back()->with('success', 'Subscription QR updated successfully.');
    }
}

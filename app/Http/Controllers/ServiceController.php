<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Beautician;
use App\Models\Category;

class ServiceController extends Controller
{
    // -------------------------------
    // Existing CRUD Methods
    // -------------------------------

    public function index()
    {
        $services = Service::all();
        $categories = Category::all();
        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required',
            'description' => 'required',
            'base_price' => 'required|numeric',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        Service::create($request->all());
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = Category::all();
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'service_name' => 'required',
            'description' => 'required',
            'base_price' => 'required|numeric',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $service->update($request->all());
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    // -------------------------------
    // New Method for Client Dashboard
    // -------------------------------
    public function getBeauticiansByCategory($category)
    {
        $beauticians = Beautician::whereHas('services', function($q) use ($category) {
            $q->where('category', $category);
        })
        ->with(['services' => function($q) use ($category) {
            $q->where('category', $category);
        }])
        ->get();

        return response()->json($beauticians);
    }
}


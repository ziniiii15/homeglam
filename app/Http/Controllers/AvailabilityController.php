<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Availability;
use App\Models\Beautician;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::with('beautician')->get();
        return view('availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        $beauticians = Beautician::all();
        return view('availabilities.create', compact('beauticians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beautician_id'=>'required|exists:beauticians,id',
            'day_of_week'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
        ]);

        Availability::create($request->all());
        return redirect()->route('availabilities.index')->with('success','Availability created successfully.');
    }

    public function show(Availability $availability)
    {
        return view('availabilities.show', compact('availability'));
    }

    public function edit(Availability $availability)
    {
        $beauticians = Beautician::all();
        return view('availabilities.edit', compact('availability','beauticians'));
    }

    public function update(Request $request, Availability $availability)
    {
        $request->validate([
            'beautician_id'=>'required|exists:beauticians,id',
            'day_of_week'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
        ]);

        $availability->update($request->all());
        return redirect()->route('availabilities.index')->with('success','Availability updated successfully.');
    }

    public function destroy(Availability $availability)
    {
        $availability->delete();
        return redirect()->route('availabilities.index')->with('success','Availability deleted successfully.');
    }
}

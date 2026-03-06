<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required|unique:discounts',
            'discount_type'=>'required|in:amount,percentage',
            'value'=>'required|numeric',
            'valid_from'=>'required|date',
            'valid_to'=>'required|date',
        ]);

        Discount::create($request->all());
        return redirect()->route('discounts.index')->with('success','Discount created successfully.');
    }

    public function show(Discount $discount)
    {
        return view('discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'code'=>'required|unique:discounts,code,'.$discount->id,
            'discount_type'=>'required|in:amount,percentage',
            'value'=>'required|numeric',
            'valid_from'=>'required|date',
            'valid_to'=>'required|date',
        ]);

        $discount->update($request->all());
        return redirect()->route('discounts.index')->with('success','Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success','Discount deleted successfully.');
    }
}

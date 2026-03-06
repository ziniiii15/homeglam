<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Client;
use App\Models\Beautician;
use App\Models\Booking;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['client','beautician','booking'])->get();
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $clients = Client::all();
        $beauticians = Beautician::all();
        $bookings = Booking::all();
        return view('reviews.create', compact('clients','beauticians','bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id'=>'required|exists:bookings,id',
            'client_id'=>'required|exists:clients,id',
            'beautician_id'=>'required|exists:beauticians,id',
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'required',
            'date'=>'required|date',
            'review_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $data = $request->all();

        if ($request->hasFile('review_image')) {
            $path = $request->file('review_image')->store('review_images', 'public');
            $data['image_url'] = 'storage/' . $path;
        }

        $review = Review::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Review created successfully.',
                'review' => $review
            ]);
        }

        return redirect()->route('reviews.index')->with('success','Review created successfully.');
    }

    public function show(Review $review)
    {
        $review->load(['client','beautician','booking']);
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $clients = Client::all();
        $beauticians = Beautician::all();
        $bookings = Booking::all();
        return view('reviews.edit', compact('review','clients','beauticians','bookings'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'booking_id'=>'required|exists:bookings,id',
            'client_id'=>'required|exists:clients,id',
            'beautician_id'=>'required|exists:beauticians,id',
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'required',
            'date'=>'required|date',
        ]);

        $review->update($request->all());
        return redirect()->route('reviews.index')->with('success','Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('reviews.index')->with('success','Review deleted successfully.');
    }
}

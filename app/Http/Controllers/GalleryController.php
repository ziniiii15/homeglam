<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Beautician;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('beautician')->get();
        return view('galleries.index', compact('galleries'));
    }

    public function create()
    {
        $beauticians = Beautician::all();
        return view('galleries.create', compact('beauticians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beautician_id'=>'required|exists:beauticians,id',
            'image_url'=>'required|url',
            'description'=>'nullable',
        ]);

        Gallery::create($request->all());
        return redirect()->route('galleries.index')->with('success','Gallery image added successfully.');
    }

    public function show(Gallery $gallery)
    {
        return view('galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $beauticians = Beautician::all();
        return view('galleries.edit', compact('gallery','beauticians'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'beautician_id'=>'required|exists:beauticians,id',
            'image_url'=>'required|url',
            'description'=>'nullable',
        ]);

        $gallery->update($request->all());
        return redirect()->route('galleries.index')->with('success','Gallery image updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return redirect()->route('galleries.index')->with('success','Gallery image deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    // Show all photos for the logged-in user
    public function index()
    {
        $photos = Auth::user()
                    ->photos()
                    ->withCount('likes')   // Add likes count
                    ->latest()
                    ->get();

        return view('users.photos.index', compact('photos'));
    }

    // Show upload form
    public function create()
    {
        return view('users.photos.create');
    }

    // Store new photo
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'caption' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('uploads/photos', 'public');

        PhotoModel::create([
            'user_id' => Auth::id(),
            'image_path' => $path,
            'caption' => $request->caption,
        ]);

        return redirect()->route('photos.index')->with('success', 'Photo uploaded!');
    }

    public function destroy($id)
    {
        $photo = PhotoModel::findOrFail($id);

        // Delete file from storage
        \Illuminate\Support\Facades\Storage::delete('public/' . $photo->image_path);

        // Delete from DB
        $photo->delete();

        return redirect()->route('photos.index')->with('success', 'Photo deleted successfully!');
    }
}
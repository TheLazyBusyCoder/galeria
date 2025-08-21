<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $req)
    {
        $search = $req->get('search');

        $photos = PhotoModel::with('user') // eager load user for name/profile pic
            ->withCount('likes') // count likes
            ->whereHas('user', function ($q) {
                $q->where('account_type', 'public'); // only public accounts
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                    })
                    ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10) // show 10 per page
            ->withQueryString(); // keep search term in pagination links

        return view('users.feed.index', compact('photos', 'search'));
    }
}
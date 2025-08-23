<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $req)
    {
        $search = $req->get('search');

        $seed = request()->get('seed', rand()); // keep same seed per pagination
        $photos = PhotoModel::with('user')
            ->withCount('likes')
            ->whereHas('user', fn($q) => $q->where('account_type', 'public'))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($q2) => 
                        $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%"))
                    ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("RAND($seed)") // stable random
            ->paginate(10)
            ->appends(['search' => $search, 'seed' => $seed]); // keep seed in URL


        return view('users.feed.index', compact('photos', 'search'));
    }
}
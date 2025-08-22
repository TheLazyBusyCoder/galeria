<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function index(Request $req)
    {
        $search = $req->get('search');

        $users = User::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })
            ->where('id' , '!=' , Auth::user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.social.index', compact('users', 'search'));
    }
}

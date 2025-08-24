<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register()
    {
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->isMethod('post') && $request->routeIs('login')) {
                // Option A: Show a standalone 429 page
                return response()->view('errors.429', [], 429);

                // Option B: Redirect back to login with inline error
                // return redirect()->route('login')
                //     ->withErrors(['username' => 'Too many login attempts. Please try again later.']);
            }
        });
    }
}

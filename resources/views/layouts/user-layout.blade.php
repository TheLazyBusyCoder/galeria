<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ Auth::user()->username }} </title>
    @if(Auth::user()->profile_picture)
        <link rel="icon" type="image/png" 
            href="{{ asset('storage/' . Auth::user()->profile_picture) }}">
    @else
        <link rel="icon" type="image/png" href="https://via.placeholder.com/32">
    @endif
    <link rel="stylesheet" href="{{ asset('theme.css') }}">
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar a,
        .navbar button {
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: var(--color-text);
            border: 1px solid transparent;
            background: none;
            cursor: pointer;
            font-weight: 500;
        }

        .navbar a:hover,
        .navbar button:hover {
            background: var(--color-surface-alt);
            border-color: var(--color-border);
        }

        .navbar a.active {
            border: 1px solid var(--color-primary);
            background: var(--color-primary);
            color: var(--color-text-inverse);
            font-weight: bold;
        }

        .navbar .nav-link {
            position: relative;
            display: inline-block;
        }

        .navbar .badge {
            position: absolute;
            top: 0;
            right: -8px;
            background: red;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 5px 8px;
            border-radius: 50%;
        }
        .nav-links {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
        .nav-brand {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            color: var(--color-text);
        }

        .nav-brand img {
            border-radius: 50%;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="layout">
        <nav class="navbar">
            <div class="nav-brand">
                <h1 style="color: var(--color-primary);">Galeria</h1>
            </div>
            <div class="nav-links">
                <a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs(['notifications']) ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell"><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge">{{ $unreadCount }}</span>
                    @endif
                </a>

                <a href="{{ route('feed.index') }}" class="{{ request()->routeIs(['feed.index']) ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                </a>
                <a href="{{ route('social.index') }}" class="{{ request()->routeIs(['social.index'  , 'users.show']) ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                </a>
                <a href="{{ route('messages.index') }}" class="{{ request()->routeIs(['messages.index' , 'messages.conversation']) ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mails-icon lucide-mails"><path d="M17 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 1-1.732"/><path d="m22 5.5-6.419 4.179a2 2 0 0 1-2.162 0L7 5.5"/><rect x="7" y="3" width="15" height="12" rx="2"/></svg>
                </a>
                <a href="{{ route('profile.view') }}" class="{{ request()->routeIs(['profile.view' , 'profile.edit' , 'profile.followers' , 'profile.following']) ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                <a href="{{ route('photos.create') }}" class="{{ request()->routeIs('photos.create') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-up-icon lucide-image-up"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/><path d="m14 19.5 3-3 3 3"/><path d="M17 22v-5.5"/><circle cx="9" cy="9" r="2"/></svg>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg></button>
                </form>
            </div>
        </nav>

        <main class="content">
            @yield('main')
        </main>

        <footer class="footer">
            <p>&copy; <script>document.write(new Date().getFullYear())</script> Galeria — thel3ox</p>
        </footer>

        @yield('js')
    </div>
</body>
</html>
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
            justify-content: center;
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
    </style>
    @yield('css')
</head>
<body>
    <div class="layout">
        <nav class="navbar">
            <a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs(['notifications']) ? 'active' : '' }}">
                Notifications
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
            </a>

            <a href="{{ route('feed.index') }}" class="{{ request()->routeIs(['feed.index']) ? 'active' : '' }}">Feed</a>
            <a href="{{ route('social.index') }}" class="{{ request()->routeIs(['social.index'  , 'users.show']) ? 'active' : '' }}">Social</a>
            <a href="{{ route('messages.index') }}" class="{{ request()->routeIs(['messages.index' , 'messages.conversation']) ? 'active' : '' }}">Inbox</a>
            <a href="{{ route('profile.view') }}" class="{{ request()->routeIs(['profile.view' , 'profile.edit' , 'profile.followers' , 'profile.following']) ? 'active' : '' }}">My Profile</a>
            <a href="{{ route('photos.create') }}" class="{{ request()->routeIs('photos.create') ? 'active' : '' }}">Upload</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
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
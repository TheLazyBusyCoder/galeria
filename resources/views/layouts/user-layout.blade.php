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
        <!-- fallback default favicon -->
        <link rel="icon" type="image/png" href="https://via.placeholder.com/32">
    @endif
</head>
<body>
    {{-- navbar --}}
    <nav>
        <table border="1" width="100%" cellspacing="0" cellpadding="8">
            <tr align="center">
                <!-- Feed -->
                <td>
                    @if(request()->routeIs('feed.index'))
                        [<strong><a href="{{ route('feed.index') }}">🏠 Feed</a></strong>]
                    @else
                        <a href="{{ route('feed.index') }}">🏠 Feed</a>
                    @endif
                </td>

                <!-- Profile -->
                <td>
                    @if(request()->routeIs('profile.view'))
                        [<strong><a href="{{ route('profile.view') }}">👤 My Profile</a></strong>]
                    @else
                        <a href="{{ route('profile.view') }}">👤 My Profile</a>
                    @endif
                </td>

                <!-- Photos -->
                {{-- <td>
                    @if(request()->routeIs('photos.index'))
                        [<strong><a href="{{ route('photos.index') }}">📸 My Photos</a></strong>]
                    @else
                        <a href="{{ route('photos.index') }}">📸 My Photos</a>
                    @endif
                </td> --}}
                <td>
                    @if(request()->routeIs('photos.create'))
                        [<strong><a href="{{ route('photos.create') }}">⬆️ Upload</a></strong>]
                    @else
                        <a href="{{ route('photos.create') }}">⬆️ Upload</a>
                    @endif
                </td>

                {{-- <!-- Followers -->
                <td>
                    @if(request()->routeIs('profile.followers'))
                        <strong><a href="{{ route('profile.followers', Auth::id()) }}">👥 Followers</a></strong>
                    @else
                        <a href="{{ route('profile.followers', Auth::id()) }}">👥 Followers</a>
                    @endif
                </td>

                <!-- Following -->
                <td>
                    @if(request()->routeIs('profile.following'))
                        <strong><a href="{{ route('profile.following', Auth::id()) }}">➡️ Following</a></strong>
                    @else
                        <a href="{{ route('profile.following', Auth::id()) }}">➡️ Following</a>
                    @endif
                </td> --}}

                <!-- Logout -->
                <td>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit">🚪 Logout</button>
                    </form>
                </td>
            </tr>
        </table>
    </nav>

    <br>

    {{-- content --}}
    <main>
        @yield('main')
    </main>
</body>
</html>
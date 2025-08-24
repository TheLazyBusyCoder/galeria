<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galeria</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('theme.css') }}">
    @yield('css')
    <style>
        header h1 {
            color: white;
        }
    </style>
    
</head>
<body>
    <div class="layout">
        <header class="navbar">
            <h1>Galeria</h1>
        </header>

        <main class="content">
            @yield('main')
        </main>

        <footer class="footer">
            <p>&copy; <script>document.write(new Date().getFullYear())</script> Galeria — thel3ox</p>
        </footer>
    </div>
    @yield('js')
</body>
</html>
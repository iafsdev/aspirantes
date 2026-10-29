<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo') · {{ config('app.name') }}</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    @stack('estilos')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <span class="navbar-brand">{{ config('app.name') }}</span>
        </div>
    </nav>

    <main class="container pb-5">
        @if (session('exito'))
            <div class="alert alert-success">{{ session('exito') }}</div>
        @endif

        @if (session('aviso'))
            <div class="alert alert-warning">{{ session('aviso') }}</div>
        @endif

        @yield('contenido')
    </main>

    @stack('scripts')
</body>
</html>

{{-- Independiente del layout principal para que pueda mostrarse aunque la aplicación falle. --}}
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
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-light">
    <main class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="text-center">
            <p class="display-1 fw-bold text-primary mb-2">@yield('codigo')</p>
            <h1 class="h4 mb-3">@yield('titulo')</h1>
            <p class="text-muted mb-4">@yield('mensaje')</p>
            <a href="/aspirantes" class="btn btn-primary">Ir al listado de aspirantes</a>
        </div>
    </main>
</body>
</html>

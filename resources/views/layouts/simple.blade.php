<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'ResumIA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- layout sin barra: login, registro y confirmar eliminacion --}}
{{-- todo va centrado en la pantalla --}}
{{-- el fondo se puede cambiar con @section('fondo', ...), si no es bg-fondo --}}
<body class="flex min-h-screen flex-col items-center justify-center gap-4 px-4 font-sans text-tinta antialiased @yield('fondo', 'bg-fondo')">

    {{-- aviso verde de exito --}}
    @if (session('ok'))
        <div class="rounded-lg border border-exito/30 bg-exito-suave px-4 py-3 text-sm font-semibold text-exito">
            {{ session('ok') }}
        </div>
    @endif

    @yield('contenido')

</body>
</html>

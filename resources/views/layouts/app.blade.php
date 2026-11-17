<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- cada vista pone su titulo con @section('titulo', ...) --}}
    <title>@yield('titulo', 'ResumIA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-fondo font-sans text-tinta antialiased">

    {{-- barra superior --}}
    <header class="flex h-16 items-center justify-between border-b border-linea bg-white px-10">
        {{-- logo a la izquierda, lleva a mis resumenes --}}
        <a href="{{ route('resumenes.index') }}" class="text-xl font-bold text-primario">ResumIA</a>

        {{-- solo si hay sesion: enlaces, nombre y boton salir --}}
        @auth
            <nav class="flex items-center gap-6">
                <a href="{{ route('resumenes.index') }}" class="text-sm font-medium text-apagado hover:text-tinta">Mis resúmenes</a>
                <a href="{{ route('resumenes.create') }}" class="text-sm font-medium text-apagado hover:text-tinta">Nuevo resumen</a>

                {{-- nombre del usuario con sesion --}}
                <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>

                {{-- salir es un formulario post (con @csrf) a la ruta logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-tinta hover:bg-fondo">
                        Salir
                    </button>
                </form>
            </nav>
        @endauth

        {{-- sin sesion (@guest) no se muestra nada a la derecha --}}
    </header>

    <main class="mx-auto max-w-[1280px] px-10 py-8">
        {{-- aviso verde de exito, llega con ->with('ok', '...') desde el controlador --}}
        @if (session('ok'))
            <div class="mb-6 rounded-lg border border-exito/30 bg-exito-suave px-4 py-3 text-sm font-semibold text-exito">
                {{ session('ok') }}
            </div>
        @endif

        {{-- aqui va el contenido de cada pantalla --}}
        @yield('contenido')
    </main>

</body>
</html>

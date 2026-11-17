@extends('layouts.simple')

@section('titulo', 'Entrar · ResumIA')

@section('contenido')
    {{-- 01 login: tarjeta centrada --}}
    <div class="flex w-full max-w-[400px] flex-col gap-4 rounded-tarjeta border border-linea bg-white p-8">

        {{-- logo y frase --}}
        <h1 class="text-[28px] font-bold text-primario">ResumIA</h1>
        <p class="text-sm text-apagado">Entra para resumir tus textos y documentos con IA.</p>

        {{-- el formulario hace post a la misma ruta login --}}
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
            @csrf

            {{-- campo correo (si las credenciales fallan, el error llega en email) --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Correo</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.cl"
                       class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('email') ? 'border-peligro' : 'border-linea' }}">
                @error('email')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>

            {{-- campo contrasena (sin old: la contrasena no se devuelve) --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Contraseña</span>
                <input type="password" name="password"
                       class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('password') ? 'border-peligro' : 'border-linea' }}">
                @error('password')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>

            {{-- boton primario de ancho completo --}}
            <button class="w-full rounded-lg bg-primario px-[18px] py-2.5 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Entrar
            </button>
        </form>

        {{-- enlace a registro --}}
        <p class="text-center text-[13px] text-apagado">
            ¿No tienes cuenta?
            <a href="{{ route('registro') }}" class="font-semibold text-primario">Crear cuenta</a>
        </p>
    </div>
@endsection

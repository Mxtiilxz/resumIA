@extends('layouts.simple')

@section('titulo', 'Crear cuenta · ResumIA')

@section('contenido')
    {{-- 02 registro: misma tarjeta que login --}}
    <div class="flex w-full max-w-[400px] flex-col gap-4 rounded-tarjeta border border-linea bg-white p-8">

        <h1 class="text-2xl font-bold">Crear cuenta</h1>
        <p class="text-sm text-apagado">Tus resúmenes quedan guardados en tu cuenta.</p>

        {{-- post a la ruta registro --}}
        <form method="POST" action="{{ route('registro') }}" class="flex flex-col gap-4">
            @csrf

            {{-- nombre --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Nombre</span>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Tu nombre"
                       class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('name') ? 'border-peligro' : 'border-linea' }}">
                @error('name')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>

            {{-- correo --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Correo</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.cl"
                       class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('email') ? 'border-peligro' : 'border-linea' }}">
                @error('email')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>

            {{-- contrasena (las contrasenas no usan old) --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Contraseña</span>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres"
                       class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('password') ? 'border-peligro' : 'border-linea' }}">
                @error('password')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>

            {{-- repetir contrasena: el nombre password_confirmation lo pide la regla confirmed --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Repite la contraseña</span>
                <input type="password" name="password_confirmation"
                       class="h-11 w-full rounded-lg border border-linea px-3 text-sm">
            </label>

            <button class="w-full rounded-lg bg-primario px-[18px] py-2.5 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Crear cuenta
            </button>
        </form>

        {{-- enlace a login --}}
        <p class="text-center text-[13px] text-apagado">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-primario">Entrar</a>
        </p>
    </div>
@endsection

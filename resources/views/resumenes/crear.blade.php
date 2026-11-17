@extends('layouts.app')

@section('titulo', 'Nuevo resumen · ResumIA')

@section('contenido')
    {{-- 04 nuevo resumen --}}

    {{-- volver a la lista --}}
    <a href="{{ route('resumenes.index') }}" class="text-sm font-semibold text-primario">← Mis resúmenes</a>
    <h1 class="mb-6 mt-2 text-2xl font-bold">Nuevo resumen</h1>

    {{-- error de groq (falta la clave, limite de uso o respuesta vacia) --}}
    @error('ia')
        <div class="mb-4 rounded-lg bg-peligro-suave px-4 py-3 text-sm font-semibold text-peligro">{{ $message }}</div>
    @enderror

    {{-- formulario: post a resumenes.store --}}
    <form method="POST" action="{{ route('resumenes.store') }}"
          class="flex flex-col gap-4 rounded-tarjeta border border-linea bg-white p-6">
        @csrf

        {{-- titulo --}}
        <label class="flex flex-col gap-1.5">
            <span class="text-[13px] font-medium">Título</span>
            <input type="text" name="titulo" value="{{ old('titulo') }}" maxlength="120" placeholder="Ej: Lectura semana 5"
                   class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('titulo') ? 'border-peligro' : 'border-linea' }}">
            @error('titulo')
                <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
            @enderror
        </label>

        {{-- texto a resumir, maxlength corta en 20000 caracteres --}}
        <label class="flex flex-col gap-1.5">
            <span class="text-[13px] font-medium">Pega tu texto</span>
            <textarea name="texto" rows="6" maxlength="20000" placeholder="Pega aquí el contenido que quieres resumir..."
                      class="w-full rounded-lg border px-3 py-2.5 text-sm {{ $errors->has('texto') ? 'border-peligro' : 'border-linea' }}">{{ old('texto') }}</textarea>
            <span class="text-xs text-apagado">Máximo 20.000 caracteres</span>
            @error('texto')
                <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
            @enderror
        </label>

        {{-- modo y extension en dos columnas iguales --}}
        <div class="grid grid-cols-2 gap-4">
            {{-- modo, por defecto desglose --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Modo</span>
                <select name="modo"
                        class="h-11 w-full rounded-lg border bg-white px-3 text-sm {{ $errors->has('modo') ? 'border-peligro' : 'border-linea' }}">
                    @foreach ($modos as $valor => $nombre)
                        <option value="{{ $valor }}" @selected(old('modo', 'desglose') === $valor)>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('modo')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>

            {{-- extension, por defecto medio --}}
            <label class="flex flex-col gap-1.5">
                <span class="text-[13px] font-medium">Extensión</span>
                <select name="extension"
                        class="h-11 w-full rounded-lg border bg-white px-3 text-sm {{ $errors->has('extension') ? 'border-peligro' : 'border-linea' }}">
                    @foreach ($extensiones as $valor => $nombre)
                        <option value="{{ $valor }}" @selected(old('extension', 'medio') === $valor)>{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('extension')
                    <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
                @enderror
            </label>
        </div>

        {{-- acciones a la derecha --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('resumenes.index') }}"
               class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-tinta hover:bg-fondo">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-lg bg-primario px-[18px] py-2.5 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Generar resumen
            </button>
        </div>
    </form>
@endsection

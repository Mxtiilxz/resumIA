@extends('layouts.app')

@section('titulo', 'Editar resumen · ResumIA')

@section('contenido')
    {{-- 06 editar resumen --}}

    <a href="{{ route('resumenes.show', $resumen) }}" class="text-sm font-semibold text-primario">← Volver al resumen</a>
    <h1 class="mb-6 mt-2 text-2xl font-bold">Editar resumen</h1>

    {{-- error de groq si se cambio el modo y fallo --}}
    @error('ia')
        <div class="mb-4 max-w-[640px] rounded-lg bg-peligro-suave px-4 py-3 text-sm font-semibold text-peligro">{{ $message }}</div>
    @enderror

    {{-- formulario put a resumenes.update --}}
    <form method="POST" action="{{ route('resumenes.update', $resumen) }}"
          class="flex max-w-[640px] flex-col gap-4 rounded-tarjeta border border-linea bg-white p-6">
        @csrf
        @method('PUT')

        {{-- titulo: old() con el valor guardado como respaldo --}}
        <label class="flex flex-col gap-1.5">
            <span class="text-[13px] font-medium">Título</span>
            <input type="text" name="titulo" value="{{ old('titulo', $resumen->titulo) }}" maxlength="120"
                   class="h-11 w-full rounded-lg border px-3 text-sm {{ $errors->has('titulo') ? 'border-peligro' : 'border-linea' }}">
            @error('titulo')
                <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
            @enderror
        </label>

        {{-- modo: queda marcado el que tenia el resumen --}}
        <label class="flex flex-col gap-1.5">
            <span class="text-[13px] font-medium">Modo</span>
            <select name="modo"
                    class="h-11 w-full rounded-lg border bg-white px-3 text-sm {{ $errors->has('modo') ? 'border-peligro' : 'border-linea' }}">
                @foreach ($modos as $valor => $nombre)
                    <option value="{{ $valor }}" @selected(old('modo', $resumen->modo) === $valor)>{{ $nombre }}</option>
                @endforeach
            </select>
            @error('modo')
                <span class="text-xs font-semibold text-peligro">{{ $message }}</span>
            @enderror
        </label>

        <p class="text-[13px] text-apagado">Si cambias el modo, la IA vuelve a generar el resumen con el texto original guardado.</p>

        {{-- acciones a la derecha --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('resumenes.show', $resumen) }}"
               class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-tinta hover:bg-fondo">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-lg bg-primario px-[18px] py-2.5 text-sm font-semibold text-white hover:bg-primario-fuerte">
                Guardar cambios
            </button>
        </div>
    </form>
@endsection

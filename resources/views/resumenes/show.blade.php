@extends('layouts.app')

@section('titulo', $resumen->titulo . ' · ResumIA')

@section('contenido')
    {{-- 05 resultado --}}

    <a href="{{ route('resumenes.index') }}" class="text-sm font-semibold text-primario">← Mis resúmenes</a>

    {{-- encabezado: titulo y chips a la izquierda, botones a la derecha --}}
    <div class="mb-6 mt-2 flex flex-wrap items-center gap-2">
        <div>
            <h1 class="text-2xl font-bold">{{ $resumen->titulo }}</h1>
            <div class="mt-2 flex gap-2">
                {{-- chip del modo --}}
                <span class="rounded-full bg-primario-suave px-2.5 py-1 text-xs font-medium text-primario">
                    {{ $modos[$resumen->modo] }}
                </span>
                {{-- chip de la extension --}}
                <span class="rounded-full bg-chip px-2.5 py-1 text-xs font-medium text-apagado">
                    {{ $extensiones[$resumen->extension] }}
                </span>
            </div>
        </div>

        <div class="ml-auto flex gap-2">
            @can('update', $resumen)
                {{-- regenerar: formulario patch, la ia hace otra version con el mismo modo y extension --}}
                <form method="POST" action="{{ route('resumenes.regenerar', $resumen) }}">
                    @csrf
                    @method('PATCH')
                    <button class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-tinta hover:bg-fondo">
                        Regenerar
                    </button>
                </form>

                <a href="{{ route('resumenes.edit', $resumen) }}"
                   class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-tinta hover:bg-fondo">
                    Editar
                </a>
            @endcan

            @can('delete', $resumen)
                <a href="{{ route('resumenes.eliminar', $resumen) }}"
                   class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-peligro hover:bg-peligro-suave">
                    Eliminar
                </a>
            @endcan
        </div>
    </div>

    {{-- error de groq al regenerar (regenerar vuelve a esta pantalla) --}}
    @error('ia')
        <div class="mb-4 rounded-lg bg-peligro-suave px-4 py-3 text-sm font-semibold text-peligro">{{ $message }}</div>
    @enderror

    {{-- dos columnas; en pantallas angostas quedan una sobre otra --}}
    <div class="flex flex-col items-start gap-5 md:flex-row">

        {{-- izquierda: texto original --}}
        <div class="flex w-full flex-col gap-3 rounded-tarjeta border border-linea bg-white p-6 md:w-[440px]">
            <h2 class="text-base font-semibold">Texto original</h2>
            {{-- whitespace-pre-line respeta los saltos de linea; max-h-96 con scroll si es muy largo --}}
            <p class="max-h-96 overflow-auto whitespace-pre-line text-[13px] text-apagado">{{ $resumen->texto_original }}</p>
            {{-- mb_strlen cuenta bien las letras con tilde --}}
            <p class="text-xs font-medium text-apagado">{{ mb_strlen($resumen->texto_original) }} caracteres</p>
        </div>

        {{-- derecha: resultado de la ia --}}
        <div class="flex w-full flex-1 flex-col gap-3 rounded-tarjeta border border-linea bg-white p-6">
            {{-- la cabecera cambia segun el modo --}}
            @if ($resumen->modo === 'desglose')
                <h2 class="text-base font-semibold">Desglose generado</h2>
            @elseif ($resumen->modo === 'resumen')
                <h2 class="text-base font-semibold">Resumen breve generado</h2>
            @else
                <h2 class="text-base font-semibold">Puntos clave generados</h2>
            @endif

            {{-- el resultado es texto plano con saltos de linea --}}
            <p class="whitespace-pre-line text-sm">{{ $resumen->resultado }}</p>

            <p class="text-xs text-apagado">Generado con IA (Groq)</p>
        </div>
    </div>
@endsection

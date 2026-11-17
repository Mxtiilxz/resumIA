@extends('layouts.app')

@section('titulo', 'Mis resúmenes · ResumIA')

@section('contenido')
    {{-- 03 mis resumenes --}}

    {{-- encabezado: titulo y cantidad a la izquierda, boton nuevo a la derecha --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Mis resúmenes</h1>
            {{-- singular si hay uno solo --}}
            @if ($resumenes->count() === 1)
                <p class="text-sm text-apagado">1 resumen guardado</p>
            @else
                <p class="text-sm text-apagado">{{ $resumenes->count() }} resúmenes guardados</p>
            @endif
        </div>

        <a href="{{ route('resumenes.create') }}"
           class="rounded-lg bg-primario px-[18px] py-2.5 text-sm font-semibold text-white hover:bg-primario-fuerte">
            + Nuevo resumen
        </a>
    </div>

    {{-- lista vacia --}}
    @if ($resumenes->isEmpty())
        <div class="rounded-tarjeta border border-linea bg-white px-6 py-12 text-center italic text-apagado">
            Todavía no tienes resúmenes.
        </div>
    @else
        {{-- una tarjeta por resumen --}}
        <div class="flex flex-col gap-5">
            @foreach ($resumenes as $resumen)
                <div class="flex items-center gap-6 rounded-tarjeta border border-linea bg-white px-6 py-5">

                    {{-- izquierda: titulo, modo y extension, inicio del resultado --}}
                    <div class="min-w-0 flex-1">
                        <h2 class="text-base font-semibold">{{ $resumen->titulo }}</h2>
                        {{-- $modos y $extensiones traducen la clave al nombre que ve el usuario --}}
                        <p class="text-xs font-medium text-primario">
                            {{ $modos[$resumen->modo] }} · {{ $extensiones[$resumen->extension] }}
                        </p>
                        {{-- line-clamp-1 corta el texto a una linea con ... --}}
                        <p class="line-clamp-1 text-[13px] text-apagado">{{ $resumen->resultado }}</p>
                    </div>

                    {{-- derecha: botones --}}
                    <div class="flex gap-2">
                        <a href="{{ route('resumenes.show', $resumen) }}"
                           class="rounded-lg bg-primario px-[18px] py-2.5 text-sm font-semibold text-white hover:bg-primario-fuerte">
                            Ver
                        </a>

                        {{-- editar y eliminar solo si la policy lo permite --}}
                        @can('update', $resumen)
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
            @endforeach
        </div>
    @endif
@endsection

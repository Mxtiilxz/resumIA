@extends('layouts.simple')

@section('titulo', 'Eliminar resumen · ResumIA')

{{-- fondo oscuro transparente, como un modal --}}
@section('fondo', 'bg-[rgba(15,23,42,0.55)]')

@section('contenido')
    {{-- 07 confirmar eliminacion --}}
    <div class="flex w-full max-w-[460px] flex-col gap-3.5 rounded-tarjeta border border-linea bg-white p-7">
        <h1 class="text-xl font-bold">¿Eliminar este resumen?</h1>
        <p class="text-sm text-apagado">
            Se eliminará "{{ $resumen->titulo }}" de forma permanente. Esta acción no se puede deshacer.
        </p>

        {{-- acciones a la derecha --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('resumenes.show', $resumen) }}"
               class="rounded-lg border border-linea bg-white px-[18px] py-2.5 text-sm font-semibold text-tinta hover:bg-fondo">
                Cancelar
            </a>

            {{-- eliminar: formulario delete a resumenes.destroy --}}
            <form method="POST" action="{{ route('resumenes.destroy', $resumen) }}">
                @csrf
                @method('DELETE')
                <button class="rounded-lg bg-peligro px-[18px] py-2.5 text-sm font-semibold text-white hover:opacity-90">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection

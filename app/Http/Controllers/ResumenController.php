<?php

namespace App\Http\Controllers;

use App\Models\Resumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResumenController extends Controller
{
    public function index(Request $request)
    {
        // Solo los del usuario con sesión, el más nuevo primero.
        $resumenes = $request->user()->resumenes()->latest()->get();

        return view('resumenes.index', [
            'resumenes'   => $resumenes,
            'modos'       => $this->modos(),
            'extensiones' => $this->extensiones(),
        ]);
    }

    public function create()
    {
        return view('resumenes.crear', [
            'modos'       => $this->modos(),
            'extensiones' => $this->extensiones(),
        ]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo'    => 'required|string|max:120',
            'texto'     => 'required|string|max:20000',
            'modo'      => 'required|in:resumen,desglose,puntos',
            'extension' => 'required|in:corto,medio,largo',
        ], [
            'titulo.required'    => 'Escribe un título.',
            'titulo.max'         => 'El título no puede pasar de :max caracteres.',
            'texto.required'     => 'Pega el texto que quieres resumir.',
            'texto.max'          => 'El texto no puede pasar de :max caracteres.',
            'modo.required'      => 'Elige un modo.',
            'extension.required' => 'Elige una extensión.',
        ]);

        $ia = $this->pedirResumen($datos['texto'], $datos['modo'], $datos['extension']);

        if (isset($ia['error'])) {
            return back()->withInput()->withErrors(['ia' => $ia['error']]);
        }

        // user_id no viene del formulario: lo pone la relación con el usuario.
        $resumen = $request->user()->resumenes()->create([
            'titulo'         => $datos['titulo'],
            'texto_original' => $datos['texto'],
            'modo'           => $datos['modo'],
            'extension'      => $datos['extension'],
            'resultado'      => $ia['resultado'],
        ]);

        return redirect()->route('resumenes.show', $resumen)
            ->with('ok', 'Resumen generado.');
    }

    public function show(Resumen $resumen)
    {
        Gate::authorize('view', $resumen);

        return view('resumenes.show', [
            'resumen'     => $resumen,
            'modos'       => $this->modos(),
            'extensiones' => $this->extensiones(),
        ]);
    }

    public function edit(Resumen $resumen)
    {
        Gate::authorize('update', $resumen);

        return view('resumenes.editar', [
            'resumen' => $resumen,
            'modos'   => $this->modos(),
        ]);
    }

    public function update(Request $request, Resumen $resumen)
    {
        Gate::authorize('update', $resumen);

        $datos = $request->validate([
            'titulo' => 'required|string|max:120',
            'modo'   => 'required|in:resumen,desglose,puntos',
        ], [
            'titulo.required' => 'Escribe un título.',
            'titulo.max'      => 'El título no puede pasar de :max caracteres.',
            'modo.required'   => 'Elige un modo.',
        ]);

        // Si cambió el modo, la IA vuelve a resumir el texto guardado.
        if ($datos['modo'] !== $resumen->modo) {
            $ia = $this->pedirResumen($resumen->texto_original, $datos['modo'], $resumen->extension);

            if (isset($ia['error'])) {
                return back()->withInput()->withErrors(['ia' => $ia['error']]);
            }

            $datos['resultado'] = $ia['resultado'];
        }

        $resumen->update($datos);

        return redirect()->route('resumenes.show', $resumen)
            ->with('ok', 'Se guardaron los cambios.');
    }

    public function regenerar(Resumen $resumen)
    {
        Gate::authorize('update', $resumen);

        $ia = $this->pedirResumen($resumen->texto_original, $resumen->modo, $resumen->extension);

        if (isset($ia['error'])) {
            return back()->withErrors(['ia' => $ia['error']]);
        }

        $resumen->update(['resultado' => $ia['resultado']]);

        return redirect()->route('resumenes.show', $resumen)
            ->with('ok', 'Resumen regenerado.');
    }

    public function eliminar(Resumen $resumen)
    {
        Gate::authorize('delete', $resumen);

        return view('resumenes.eliminar', ['resumen' => $resumen]);
    }

    public function destroy(Resumen $resumen)
    {
        Gate::authorize('delete', $resumen);

        $resumen->delete();

        return redirect()->route('resumenes.index')
            ->with('ok', "Se eliminó «{$resumen->titulo}».");
    }

    /**
     * Le pide el resumen a Groq. Devuelve ['resultado' => ...] o ['error' => ...].
     */
    private function pedirResumen(string $texto, string $modo, string $extension): array
    {
        // Sin clave, ni lo intentes.
        if (blank(config('services.groq.key'))) {
            Log::warning('Falta GROQ_API_KEY en el .env: no se llamó a Groq');

            return ['error' => 'Falta la clave de Groq en el .env.'];
        }

        // Cada modo tiene su propio mensaje de sistema.
        $formato = match ($modo) {
            'resumen'  => 'Escribe un resumen breve en prosa, solo con párrafos.',
            'desglose' => 'Escribe un desglose en tres partes, cada una con su título en una línea propia: '
                . '"Objetivo general", "Puntos principales" (cada punto en una línea que empiece con "• ") '
                . 'y "Conclusión".',
            'puntos'   => 'Escribe solo una lista de puntos clave, cada uno en una línea que empiece con "• ".',
        };

        $largo = match ($extension) {
            'corto' => 'Que sea corto: un párrafo o 3 a 5 puntos.',
            'medio' => 'Que sea de largo medio: dos o tres párrafos o 5 a 8 puntos.',
            'largo' => 'Que sea largo y detallado: cuatro o cinco párrafos o 8 a 12 puntos.',
        };

        $rol = 'Eres ResumIA, un asistente que resume textos. Responde únicamente con el resultado, '
            . 'en español, sin saludos, sin comentarios y sin formato markdown. '
            . 'Usa solo la información del texto. ' . $formato . ' ' . $largo;

        $respuesta = Http::withToken(config('services.groq.key'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => config('services.groq.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $rol],
                    ['role' => 'user', 'content' => $texto],
                ],
            ]);

        if ($respuesta->failed()) {
            Log::error('Groq devolvió un error', [
                'status' => $respuesta->status(),
                'body' => $respuesta->json(),
            ]);

            if ($respuesta->status() === 429) {
                return ['error' => 'Se alcanzó el límite de uso de Groq. Espera un minuto e intenta de nuevo.'];
            }

            return ['error' => 'No se pudo generar el resumen. Intenta de nuevo.'];
        }

        $resultado = $respuesta->json('choices.0.message.content');

        if (blank($resultado)) {
            Log::warning('Groq respondió sin texto', [
                'body' => $respuesta->json(),
            ]);

            return ['error' => 'La respuesta vino vacía. Intenta de nuevo.'];
        }

        return ['resultado' => $resultado];
    }

    /**
     * Los modos que se pueden elegir, con el nombre que ve el usuario.
     */
    private function modos(): array
    {
        return [
            'resumen'  => 'Resumen breve',
            'desglose' => 'Desglose',
            'puntos'   => 'Puntos clave',
        ];
    }

    private function extensiones(): array
    {
        return [
            'corto' => 'Corto',
            'medio' => 'Medio',
            'largo' => 'Largo',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// modelo de un resumen: cada objeto resumen es una fila de la tabla resumenes
class Resumen extends Model
{
    // el pluralizador de laravel es en ingles: de resumen deduce "resumens"
    // nuestra tabla se llama resumenes, asi que hay que decirlo
    protected $table = 'resumenes';

    // los campos que se pueden llenar de una vez con create() o update()
    // user_id no esta a proposito: el dueno no lo elige el formulario,
    // lo pone la relacion $usuario->resumenes()->create(...)
    protected $fillable = ['titulo', 'texto_original', 'modo', 'extension', 'resultado'];

    // relacion: un resumen pertenece a un usuario
    // uso: $resumen->usuario->name
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

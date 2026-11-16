<?php

namespace App\Policies;

use App\Models\Resumen;
use App\Models\User;

class ResumenPolicy
{
    /**
     * La regla de ResumIA: cada quien ve y maneja solo sus resúmenes.
     *
     * No hay que registrar esta clase en ninguna parte: Laravel la encuentra
     * por el nombre (Resumen -> ResumenPolicy).
     */
    public function view(User $user, Resumen $resumen): bool
    {
        return $user->id === $resumen->user_id;
    }

    public function update(User $user, Resumen $resumen): bool
    {
        return $user->id === $resumen->user_id;
    }

    public function delete(User $user, Resumen $resumen): bool
    {
        return $user->id === $resumen->user_id;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Conclusion;
use App\Models\Entrada;
use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{

    public function __invoke()
    {
        $user = auth()->user();

        $entries_query = $this->entriesForUser($user);

        $entries_count = (clone $entries_query)->count();

        $entries = $entries_query
            ->with([
                'origen',
                'destino',
                'asignadoA',
                'seguimientos' => fn ($query) => $query->latest('id'),
                'conclusiones' => fn ($query) => $query->latest('id')->with('creadoPor'),
            ])
            ->orderBy('id', 'desc')
            ->orderBy('fecha_termino', 'asc')
            ->take(6)
            ->get();

        $trackings_count = $this->countForUser(Seguimiento::query(), $user);
        $conclusions_count = $this->countForUser(Conclusion::query(), $user);

        return view('dashboard', compact('entries', 'entries_count', 'trackings_count', 'conclusions_count'));

    }

    private function entriesForUser($user): Builder
    {

        if ($user->hasRole('Administrador')) {

            return Entrada::query();

        }

        if ($user->hasRole(['Titular', 'Oficialia de partes'])) {

            return Entrada::query()->where(function ($query) use ($user) {
                $query->where('creado_por', $user->id)
                    ->orWhereHas('asignadoA', fn ($assigned) => $assigned->where('user_id', $user->id));
            });

        }

        return Entrada::query()->whereHas('asignadoA', fn ($query) => $query->where('user_id', $user->id));

    }

    private function countForUser(Builder $query, $user): int
    {

        if ($user->hasRole('Administrador')) {

            return $query->count();

        }

        $oficina_id = $user->oficina?->id;

        return $oficina_id ? $query->where('oficina_id', $oficina_id)->count() : 0;

    }

}

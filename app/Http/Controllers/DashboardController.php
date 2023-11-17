<?php

namespace App\Http\Controllers;

use App\Models\Conclusion;
use App\Models\Entrada;
use App\Models\Seguimiento;

class DashboardController extends Controller
{

    public function __invoke()
    {

        if(auth()->user()->hasRole(['Titular', 'Administrador'])){

            $entries_count = Entrada::count();
            $entries = Entrada::with('origen')->where('oficina_id', auth()->user()->oficina->id)->orderBy('id','desc')->orderBy('fecha_termino', 'asc')->take(5)->get();
            $trackings_count = Seguimiento::count();
            $trackings = Seguimiento::with('entrada')->where('oficina_id', auth()->user()->oficina->id)->take(5)->get();
            $conclusions_count = Conclusion::count();
            $conclusions = Conclusion::with('entrada')->where('oficina_id', auth()->user()->oficina->id)->take(5)->get();

            return view('dashboard', compact('entries', 'trackings', 'conclusions','entries_count', 'trackings_count', 'conclusions_count'));

        }elseif(auth()->user()->hasRole('Usuario')){

            $entries_count = Entrada::count();
            $entries = Entrada::where('oficina_id', auth()->user()->officeBelonging->id)->take(5)->get();
            $trackings_count = Seguimiento::count();
            $trackings = Seguimiento::with('entrada')->where('oficina_id', auth()->user()->officeBelonging->id)->take(5)->get();
            $conclusions_count = Conclusion::count();
            $conclusions = Conclusion::with('entrada')->where('oficina_id', auth()->user()->officeBelonging->id)->take(5)->get();

            return view('dashboard', compact('entries', 'trackings', 'conclusions','entries_count', 'trackings_count', 'conclusions_count'));

        }

    }

}

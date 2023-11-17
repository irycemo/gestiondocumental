<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
     public function __invoke(Entrada $entrada)
     {

        $entrada->load('seguimientos.creadoPor', 'conclusiones.creadoPor');

        return view('entrada', compact('entrada'));

     }
}

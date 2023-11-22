<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
     public function __invoke(Entrada $entrada)
     {

        $entrada->load('seguimientos.creadoPor', 'seguimientos.files', 'conclusiones.creadoPor', 'conclusiones.files');

        return view('entrada', compact('entrada'));

     }
}

<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Tracking;
use App\Models\Conclusion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {

        if(auth()->user()->office){

            $entries_count = Entrie::count();
            $entries = Entrie::with('origen')->where('office_id', auth()->user()->office->id)->orderBy('id','desc')->orderBy('fecha_termino', 'asc')->take(5)->get();
            $trackings_count = Tracking::count();
            $trackings = Tracking::with('entrie')->where('office_id', auth()->user()->office->id)->take(5)->get();
            $conclusions_count = Conclusion::count();
            $conclusions = Conclusion::with('entrie')->where('office_id', auth()->user()->office->id)->take(5)->get();

        }elseif(auth()->user()->officeBelonging){

            $entries_count = Entrie::count();
            $entries = Entrie::where('office_id', auth()->user()->officeBelonging->id)->take(5)->get();
            $trackings_count = Tracking::count();
            $trackings = Tracking::with('entrie')->where('office_id', auth()->user()->officeBelonging->id)->take(5)->get();
            $conclusions_count = Conclusion::count();
            $conclusions = Conclusion::with('entrie')->where('office_id', auth()->user()->officeBelonging->id)->take(5)->get();

        }


        return view('dashboard', compact('entries', 'trackings', 'conclusions','entries_count', 'trackings_count', 'conclusions_count'));
    }
}

<?php

namespace App\Observers;

use App\Models\entrada;
use App\Models\Conclusion;
use App\Models\Seguimiento;
use App\Jobs\NotificacionesJob;
use Illuminate\Support\Facades\Storage;

class EntradaObserver
{
    /**
     * Handle the entrada "created" event.
     */
    public function created(Entrada $entrada): void
    {

    }

    /**
     * Handle the entrada "updated" event.
     */
    public function updated(Entrada $entrada): void
    {
        //
    }

    /**
     * Handle the entrada "deleted" event.
     */
    public function deleted(Entrada $entrada): void
    {
        //
    }

    public function deleting(Entrada $entrada)
    {

        $trackings = Seguimiento::with('files')->where('entrada_id', $entrada->id)->get();

        if($trackings->count()){

            foreach ($trackings as $tracking) {

                foreach($tracking->files as $file){

                    Storage::disk('pdfs')->delete($file->url);

                    $file->delete();

                }

            }

        }

        $conclusions = Conclusion::with('files')->where('entrada_id', $entrada->id)->get();

        if($conclusions->count()){

            foreach ($conclusions as $conclusion) {

                foreach($conclusion->files as $file){

                    Storage::disk('pdfs')->delete($file->url);

                    $file->delete();

                }

            }

        }

        if($entrada->files()->count()){

            foreach ($entrada->files as $file) {

                Storage::disk('pdfs')->delete($file->url);

                $file->delete();
            }

        }

    }

    /**
     * Handle the entrada "restored" event.
     */
    public function restored(Entrada $entrada): void
    {
        //
    }

    /**
     * Handle the entrada "force deleted" event.
     */
    public function forceDeleted(Entrada $entrada): void
    {
        //
    }
}

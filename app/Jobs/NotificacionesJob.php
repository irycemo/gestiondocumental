<?php

namespace App\Jobs;

use Throwable;
use App\Models\User;
use App\Models\Entrada;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\AsignacionNotification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class NotificacionesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(int $user, public int $folio){

        $this->user = User::find($user);

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $this->user->notify(new AsignacionNotification($this->folio));



    }

}

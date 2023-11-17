<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Model::shouldBeStrict();

        LogViewer::auth(function ($request) {

            if(Auth::user()->hasRole('Administrador'))
                return true;
            else
                abort(401, 'Unauthorized');

        });

        if(!env('LOCAL')){

            Livewire::setScriptRoute(function ($handle) {
                return Route::get('/gestiondocumental/public/livewire/livewire.js', $handle);
            });

            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/gestiondocumental/public/livewire/update', $handle);
            });

        }

    }
}

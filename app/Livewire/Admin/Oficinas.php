<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Oficina;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

class Oficinas extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $titulares;

    public Oficina $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.name' => 'required',
            'modelo_editar.titular' => 'nullable'
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.name' => 'nombre',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Oficina::make();
    }

    public function abrirModalEditar(Oficina $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            $this->modelo_editar->creado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La oficina se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear oficina por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La oficina se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar oficina por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $permiso = Oficina::find($this->selected_id);

            $permiso->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La oficina se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar oficina por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->titulares = User::whereHas('roles', function($q){
            $q->where('name', 'Titular');
        })->get();

    }

    public function render()
    {

        $oficinas = Oficina::with('creadoPor', 'actualizadoPor', 'uTitular')
                                ->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhereHas('uTitular', function($q){
                                    $q->where('name', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.oficinas', compact('oficinas'))->extends('layouts.admin');

    }

}

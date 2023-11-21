<?php

namespace App\Livewire;

use App\Models\File;
use App\Models\Entrada;
use Livewire\Component;
use App\Models\Seguimiento;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Seguimientos extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use WithFileUploads;

    public $files = [];
    public $files_edit = [];
    public $file_id;

    public $modalEliminar = false;
    public $entradas;

    public Seguimiento $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.oficio_respuesta' => 'required',
            'modelo_editar.fecha_respuesta' => 'required|date',
            'modelo_editar.comentario' => 'required',
            'modelo_editar.entrada_id' => 'required',
            'files.*' => 'mimes:pdf',
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.oficio_respuesta' => 'oficio de oficio',
        'modelo_editar.fecha_respuesta' => 'fecha de respuesta',
        'files.*.mimes' => 'Solo se admiten archivos PDF',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Seguimiento::make();
    }

    public function updatedModeloEditarComentario(){

        $this->dispatch('quill-get');

    }

    public function abrirModalEditar(Seguimiento $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->files_edit = File::where('fileable_id', $modelo->id)->where('fileable_type', 'App\Models\Seguimiento')->get();

    }

    public function openModalDeleteFile($id){

        $this->file_id = $id;

        $this->modalEliminar = true;

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->modelo_editar->oficina_id = auth()->user()->oficina_id;
                $this->modelo_editar->creado_por = auth()->user()->id;
                $this->modelo_editar->save();

                if(isset($this->files)){

                    foreach($this->files as $file){

                        $pdf = $file->store('/', 'pdfs');

                        File::create([
                            'fileable_id' => $this->modelo_editar->id,
                            'fileable_type' => 'App\Models\Seguimiento',
                            'url' => $pdf
                        ]);
                    }

                    $this->dispatch('removeFiles');
                }

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "El seguimiento se creó con éxito."]);

            });

        } catch (\Throwable $th){

            Log::error("Error al crear seguimiento por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $this->modelo_editar->id,
                        'fileable_type' => 'App\Models\Seguimiento',
                        'url' => $pdf
                    ]);
                }

                $this->dispatch('removeFiles');
            }

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El seguimiento se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar seguimiento por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $seguimiento = Seguimiento::find($this->selected_id);

            $seguimiento->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El seguimiento se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar seguimiento por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrarArchivo(){

        try {

            $file = File::findorFail($this->file_id);

            Storage::disk('pdfs')->delete($file->url);

            $file->delete();

            $this->dispatch('showMessage',['success', "El archivo ha sido eliminado con éxito."]);

            $this->files_edit = File::where('fileable_id', $this->modelo_editar->id)->where('fileable_type', 'App\Models\Seguimiento')->get();

            $this->modalEliminar = false;

        } catch (\Throwable $th) {

            Log::error("Error al borrar archivo de entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);
            $this->resetearTodo();
        }
    }

    public function mount(){

        $this->crearModeloVacio();

        array_push($this->fields, 'files', 'files_edit', 'file_id', 'modalEliminar');

        if(auth()->user()->hasRole(['Titular', 'Usuario']))

            $this->entradas = Entrada::select('id', 'folio', 'numero_oficio')
                                        ->withCount('conclusiones')
                                        ->whereHas('asignadoA', function($q){
                                            return $q->where('user_id', auth()->id());
                                        })
                                        ->orderBy('folio')->get()
                                        ->where('conclusiones_count', 0);

        else
            $this->entradas = Entrada::select('id', 'folio', 'numero_oficio')
                                        ->withCount('conclusiones')
                                        ->whereHas('asignadoA', function($q){
                                            return $q->where('user_id', auth()->id());
                                        })
                                        ->orderBy('folio')->get()
                                        ->where('conclusiones_count', 0);

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $seguimientos = Seguimiento::with('creadoPor', 'actualizadoPor', 'entrada')
                                ->where('oficio_respuesta', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Titular', 'Usuario'])){

            $seguimientos = Seguimiento::with('creadoPor', 'actualizadoPor', 'entrada')
                                ->where('oficina_id', auth()->user()->id)
                                ->where('oficio_respuesta', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }

        return view('livewire.seguimientos', compact('seguimientos'))->extends('layouts.admin');

    }

}

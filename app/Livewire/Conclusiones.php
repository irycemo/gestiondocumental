<?php

namespace App\Livewire;

use App\Models\File;
use App\Models\Entrada;
use Livewire\Component;
use App\Models\Conclusion;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Conclusiones extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use WithFileUploads;

    public $files = [];
    public $files_edit = [];
    public $file_id;

    public $modalEliminar = false;
    public $entradas;

    public Conclusion $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.comentario' => 'required',
            'modelo_editar.entrada_id' => 'required',
            'files.*' => 'mimes:pdf',
         ];
    }

    protected $validationAttributes  = [
        'files.*.mimes' => 'Solo se admiten archivos PDF',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Conclusion::make();
    }

    public function updatedModeloEditarComentario(){

        $this->dispatch('quill-get');

    }

    public function abrirModalEditar(Conclusion $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->files_edit = File::where('fileable_id', $modelo->id)->where('fileable_type', 'App\Models\Conclusion')->get();

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
                            'fileable_type' => 'App\Models\Conclusion',
                            'url' => $pdf
                        ]);
                    }

                    $this->dispatch('removeFiles');
                }

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "La conclusión se creó con éxito."]);

            });

        } catch (\Throwable $th){

            Log::error("Error al crear conclusion por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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
                        'fileable_type' => 'App\Models\Conclusion',
                        'url' => $pdf
                    ]);
                }

                $this->dispatch('removeFiles');
            }

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La conclusión se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar conclusion por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $conclusion = Conclusion::find($this->selected_id);

            $conclusion->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La conclusión se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar conclusion por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

            $this->files_edit = File::where('fileable_id', $this->modelo_editar->id)->where('fileable_type', 'App\Models\Conclusion')->get();

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
                                        ->where('creado_por', auth()->id())
                                        ->orWhereHas('asignadoA', function($q){
                                            return $q->where('user_id', auth()->id());
                                        })
                                        ->orderBy('folio')->get();

        else
            $this->entradas = Entrada::select('id', 'folio', 'numero_oficio')
                                        ->orderBy('folio')->get();


    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $conclusiones = Conclusion::with('creadoPor', 'actualizadoPor', 'entrada')
                                ->orWhereHas('entrada', function ($q){
                                    $q->where('numero_oficio', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('folio', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Titular', 'Usuario'])){

            $conclusiones = Conclusion::with('creadoPor', 'actualizadoPor', 'entrada')
                                ->where('creado_por', auth()->user()->id)
                                ->whereHas('entrada', function ($q){
                                    $q->where('numero_oficio', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('folio', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }

        return view('livewire.conclusiones', compact('conclusiones'))->extends('layouts.admin');
    }
}

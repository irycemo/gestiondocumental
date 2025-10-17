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
    public $entradas_seleccionadas = [];

    public $entrada_id_seleccionada;

    public Seguimiento $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.oficio_respuesta' => 'required',
            'modelo_editar.date_for_editing' => 'required|date',
            'modelo_editar.comentario' => 'required',
            'entrada_id_seleccionada' => 'required',
            'files.*' => 'mimes:pdf',
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.oficio_respuesta' => 'oficio de oficio',
        'modelo_editar.date_for_editing' => 'fecha de respuesta',
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

        $this->entrada_id_seleccionada = $this->modelo_editar->entrada_id;

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
                $this->modelo_editar->entrada_id = $this->entrada_id_seleccionada;
                $this->modelo_editar->save();

                if(isset($this->files)){

                    foreach($this->files as $file){

                        if(app()->isProduction()){

                            $pdf = $file->store(config('services.ses.ruta_archivos'), 's3');

                        }else{

                            $pdf = $file->store('/', 'pdfs');

                        }

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
            $this->modelo_editar->entrada_id = $this->entrada_id_seleccionada;
            $this->modelo_editar->save();

            if(isset($this->files)){

                DB::transaction(function () {

                    foreach($this->files as $file){

                        if(app()->isProduction()){

                            $pdf = $file->store(config('services.ses.ruta_archivos'), 's3');

                        }else{

                            $pdf = $file->store('/', 'pdfs');

                        }

                        File::create([
                            'fileable_id' => $this->modelo_editar->id,
                            'fileable_type' => 'App\Models\Seguimiento',
                            'url' => $pdf
                        ]);
                    }

                });

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

            DB::transaction(function () {

                $seguimiento = Seguimiento::find($this->selected_id);

                foreach ($seguimiento->files as $file) {

                    if(app()->isProduction()){

                        Storage::disk('s3')->delete($file->url);

                    }else{

                        Storage::disk('pdfs')->delete($file->url);

                    }

                }

                $seguimiento->delete();

            });

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

            DB::transaction(function () {

                $file = File::findorFail($this->file_id);

                if(app()->isProduction()){

                    Storage::disk('s3')->delete($file->url);

                }else{

                    Storage::disk('pdfs')->delete($file->url);

                }

                $file->delete();

            });

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

        if(auth()->user()->hasRole(['Titular', 'Usuario'])){

            $this->entradas = Entrada::select('id', 'folio', 'numero_oficio')
                                        ->whereHas('asignadoA', function($q){
                                            return $q->where('user_id', auth()->id());
                                        })
                                        ->orWhere('creado_por', auth()->id())
                                        ->whereDoesntHave('conclusiones')
                                        ->get()
                                        ->map(function ($entrada){
                                            return [
                                                'id' => $entrada->id,
                                                'entrada' => $entrada->folio . ' - ' . $entrada->numero_oficio
                                            ];
                                        });

        }else{

            $this->entradas = Entrada::select('id', 'folio', 'numero_oficio')
                                        ->orderBy('folio')
                                        ->whereDoesntHave('conclusiones')
                                        ->get()
                                        ->map(function ($entrada){
                                            return [
                                                'id' => $entrada->id,
                                                'entrada' => $entrada->folio . ' - ' . $entrada->numero_oficio
                                            ];
                                        });

        }



    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $seguimientos = Seguimiento::with('creadoPor', 'actualizadoPor', 'entrada')
                                ->where('oficio_respuesta', 'LIKE', '%' . $this->search . '%')
                                ->orWhereHas('entrada', function ($q){
                                    $q->where('numero_oficio', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('folio', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Titular', 'Usuario'])){

            $seguimientos = Seguimiento::with('creadoPor', 'actualizadoPor', 'entrada')
                                ->where('creado_por', auth()->user()->id)
                                ->where(function ($q){
                                    $q->where('oficio_respuesta', 'LIKE', '%' . $this->search . '%')
                                        ->orWhereHas('entrada', function ($q){
                                            $q->where('numero_oficio', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('folio', 'LIKE', '%' . $this->search . '%');
                                        });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }

        return view('livewire.seguimientos', compact('seguimientos'))->extends('layouts.admin');

    }

}

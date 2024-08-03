<?php

namespace App\Livewire;

use App\Models\File;
use App\Models\User;
use App\Models\Entrada;
use App\Models\Oficina;
use Livewire\Component;
use App\Models\Dependencia;
use Livewire\WithPagination;
use App\Jobs\NotificacionesJob;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Entradas extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use WithFileUploads;

    public $files = [];
    public $files_edit = [];
    public $file_id;
    public $asignados = [];

    public $oficinas;
    public $dependencias;
    public $usuarios;
    public $modalEliminar = false;

    public Entrada $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.numero_oficio' => 'required',
            'modelo_editar.asunto' => 'required',
            'modelo_editar.date_for_editing' => 'required|date',
            'modelo_editar.destinatario' => 'required',
            'modelo_editar.dependencia_id' => 'required',
            'files.*' => 'mimes:pdf',
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.numero_oficio' => 'número de oficio',
        'modelo_editar.date_for_editing' => 'fecha de termino',
        'modelo_editar.dependencia_id' => 'dependencia',
        'files.*.mimes' => 'Solo se admiten archivos PDF',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Entrada::make();
    }

    public function updatedModeloEditarAsunto(){

        $this->dispatch('quill-get');

    }

    public function abrirModalEditar(Entrada $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        foreach($modelo->asignadoA as $user){

            array_push($this->asignados, (string)$user['id']);

        }

        $this->files_edit = File::where('fileable_id', $modelo->id)->where('fileable_type', 'App\Models\Entrada')->get();

        $this->dispatch('select2');

    }

    public function openModalDeleteFile($id){

        $this->file_id = $id;

        $this->modalEliminar = true;

    }

    public function guardar(){

        $this->validate();

        try {

            /* DB::transaction(function () { */

                $this->modelo_editar->folio = (Entrada::max('folio') ?? 0) + 1;
                $this->modelo_editar->oficina_id = auth()->user()->oficina_id;
                $this->modelo_editar->creado_por = auth()->user()->id;
                $this->modelo_editar->save();

                if(isset($this->files)){

                    foreach($this->files as $file){

                        $pdf = $file->store('/', 'pdfs');

                        File::create([
                            'fileable_id' => $this->modelo_editar->id,
                            'fileable_type' => 'App\Models\Entrada',
                            'url' => $pdf
                        ]);
                    }

                    $this->dispatch('removeFiles');
                }

                $this->modelo_editar->asignadoA()->attach($this->asignados);

                foreach ($this->asignados as $asignado) {

                    dispatch(new NotificacionesJob(intval($asignado), $this->modelo_editar->folio));

                }

                $this->resetearTodo();

                $this->dispatch('mostrarMensaje', ['success', "La entrada se creó con éxito."]);

            /* }); */

        } catch (\Throwable $th){

            Log::error("Error al crear entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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
                        'fileable_type' => 'App\Models\Entrada',
                        'url' => $pdf
                    ]);
                }

                $this->dispatch('removeFiles');
            }

            $this->modelo_editar->asignadoA()->sync($this->asignados);

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La entrada se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $permiso = Entrada::find($this->selected_id);

            $permiso->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La entrada se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
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

            $this->files_edit = File::where('fileable_id', $this->modelo_editar->id)->where('fileable_type', 'App\Models\Entrada')->get();

            $this->modalEliminar = false;

        } catch (\Throwable $th) {

            Log::error("Error al borrar archivo de entrada por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);
        }
    }

    public function mount(){

        $this->crearModeloVacio();

        array_push($this->fields, 'files', 'files_edit', 'file_id', 'asignados', 'modalEliminar');

        $this->dependencias = Dependencia::orderBy('name')->get();

        $this->oficinas = Oficina::orderBy('name')->get();

        if(auth()->user()->hasRole('Titular'))
            $this->usuarios = User::where('oficina_id', auth()->user()->oficina_id)->orderBy('name')->get();
        else
            $this->usuarios = User::orderBy('name')->get();
    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $entradas = Entrada::with('creadoPor', 'actualizadoPor', 'origen', 'destino', 'asignadoA')
                                ->withCount(['seguimientos', 'conclusiones'])
                                ->where('folio', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('asunto', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('numero_oficio', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole('Titular')){

            $entradas = Entrada::with('creadoPor', 'actualizadoPor', 'origen', 'destino', 'asignadoA')
                                ->withCount(['seguimientos', 'conclusiones'])
                                ->whereHas('asignadoA', function($q){
                                    return $q->where('user_id', auth()->id());
                                })
                                ->where(function($q){
                                    $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('asunto', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_oficio', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orWhere('creado_por', auth()->id())
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole('Usuario')){

            $entradas = Entrada::with('creadoPor', 'actualizadoPor', 'origen', 'destino', 'asignadoA')
                                ->withCount(['seguimientos', 'conclusiones'])
                                ->whereHas('asignadoA', function($q){
                                    return $q->where('user_id', auth()->id());
                                })
                                ->where(function($q){
                                    $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('asunto', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_oficio', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole(['Oficialia de partes'])){

            $entradas = Entrada::with('creadoPor', 'actualizadoPor', 'origen', 'destino', 'asignadoA')
                                ->where('creado_por', auth()->id())
                                ->where(function($q){
                                    $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('asunto', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_oficio', 'LIKE', '%' . $this->search . '%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        }

        return view('livewire.entradas', compact('entradas'))->extends('layouts.admin');

    }

}

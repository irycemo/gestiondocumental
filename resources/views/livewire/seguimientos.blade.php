<div class="">

    <div class="mb-6">

        <x-header>Seguimientos</x-header>

        <div class="flex justify-between">

            <div class="flex gap-3">

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

            @can('Crear seguimiento')

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="abrirModalCrear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar nuevo seguimineto

                </button>

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

            @endcan

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('oficio_respuesta')" :direction="$sort === 'oficio_respuesta' ? $direction : null" >Oficio de respuesta</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('fecha_respuesta')" :direction="$sort === 'fecha_respuesta' ? $direction : null" >Fecha de respuesta</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('entrada_id')" :direction="$sort === 'entrada_id' ? $direction : null" >Entrada</x-table.heading>
                <x-table.heading>Comentario</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($seguimientos as $seguimineto)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $seguimineto->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Oficio de respuesta</span>

                            {{ $seguimineto->oficio_respuesta }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de respuesta</span>

                            {{ $seguimineto->fecha_respuesta->format('d-m-Y') }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Entrada</span>

                            {{ $seguimineto->entrada->folio }}-{{ $seguimineto->entrada->numero_oficio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Comentario</span>

                            {!! $seguimineto->comentario !!}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>


                            <span class="font-semibold">@if($seguimineto->creadoPor != null)Registrado por: {{$seguimineto->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{ $seguimineto->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($seguimineto->actualizadoPor != null)Actualizado por: {{$seguimineto->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $seguimineto->updated_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                            <div class="flex justify-center lg:justify-start gap-2">

                                @can('Editar seguimiento')

                                    <x-button-blue
                                        wire:click="abrirModalEditar({{ $seguimineto->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>

                                        <span>Editar</span>

                                    </x-button-blue>

                                @endcan

                                @can('Borrar seguimiento')

                                    <x-button-red
                                        wire:click="abrirModalBorrar({{ $seguimineto->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>

                                        <span>Eliminar</span>

                                    </x-button-red>

                                @endcan

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="9">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="9" class="bg-gray-50">

                        {{ $seguimientos->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nuevo seguimiento
            @elseif($editar)
                Editar seguimiento
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.oficio_respuesta" label="Oficio respuesta" :error="$errors->first('modelo_editar.oficio_respuesta')" class="w-full">

                    <x-input-text id="modelo_editar.oficio_respuesta" wire:model="modelo_editar.oficio_respuesta" />

                </x-input-group>

                <x-input-group for="modelo_editar.date_for_editing" label="Fecha respuesta" :error="$errors->first('modelo_editar.date_for_editing')" class="w-full">

                    <x-input-date id="modelo_editar.date_for_editing" wire:model.live="modelo_editar.date_for_editing" />

                </x-input-group>

                <x-input-group for="modelo_editar.entrada_id" label="Entrada" :error="$errors->first('modelo_editar.entrada_id')" class="w-full">

                    <x-input-select id="modelo_editar.entrada_id" wire:model="modelo_editar.entrada_id" class="">

                        <option value="">Seleccione una opción</option>

                        @foreach ($entradas as $entrada)

                            <option value="{{ $entrada->id }}">{{ $entrada->folio }} - {{ $entrada->numero_oficio }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <div class="flex flex-col md:flex-row justify-between gap-3 mb-3 w-full">

                    <x-input-group for="modelo_editar.comentario" label="Comentario" :error="$errors->first('modelo_editar.comentario')" class="w-full">

                        <x-input-text-area wire:model="modelo_editar.comentario" />

                    </x-input-group>

                </div>

            </div>

            <div class="flex flex-row md:flex-row justify-between gap-3 mb-3">

                <div class="w-full">

                    <x-filepond wire:model="files" multiple />

                </div>

            </div>

            <x-input-group for="files.*" label="" :error="$errors->first('files.*')" class="w-full">

                <div class="flex flex-row flex-wrap gap-2 items-center mb-2">

                    @foreach ($files_edit as $file)

                            <div class="flex gap-2 bg-red-200 rounded-full p-1">

                                <a
                                    href="{{ Storage::disk('pdfs')->url($file['url'])}}"
                                    target="_blank"
                                    class="bg-red-400 hover:shadow-lg text-white text-xs px-3 py-1 rounded-full hover:bg-red-700 focus:outline-none w-auto"
                                >
                                PDF {{ $loop->iteration }}
                                </a>

                                <button
                                    wire:click="openModalDeleteFile({{$file['id']}})"
                                    wire:loading.attr="disabled"
                                    wire:target="openModalDeleteFile({{$file['id']}})"
                                    class="bg-red-400 hover:shadow-lg text-white text-xs px-3 py-1 rounded-full hover:bg-red-700 flex focus:outline-none"
                                >

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>

                                </button>

                            </div>

                    @endforeach

                </div>

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardar"
                        wire:loading.attr="disabled"
                        wire:target="guardar">

                        <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizar"
                        wire:loading.attr="disabled"
                        wire:target="actualizar">

                        <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetearTodo"
                    wire:loading.attr="disabled"
                    wire:target="resetearTodo"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalBorrar" maxWidth="sm">

        <x-slot name="title">
            Eliminar seguimiento
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar el seguimiento? No sera posible recuperar la información.
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalBorrar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="borrar"
                wire:loading.attr="disabled"
                wire:target="borrar"
            >
                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

    <x-confirmation-modal wire:model="modalEliminar" maxWidth="sm">

        <x-slot name="title">
            Eliminar archivo
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar el archivo? No sera posible recuperar la información.
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalEliminar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="borrarArchivo"
                wire:loading.attr="disabled"
                wire:target="borrarArchivo"
            >
                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>

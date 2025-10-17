@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <div class="mb-6">

        <x-header>Entradas</x-header>

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

            @can('Crear entrada')

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="abrirModalCrear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar nueva entrada

                </button>

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

            @endcan

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('folio')" :direction="$sort === 'folio' ? $direction : null" >Folio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('numero_oficio')" :direction="$sort === 'numero_oficio' ? $direction : null" >Número de oficio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('asunto')" :direction="$sort === 'asunto' ? $direction : null" >Asunto</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('dependencia_id')" :direction="$sort === 'dependencia_id' ? $direction : null" >Origen</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('destinatario')" :direction="$sort === 'destinatario' ? $direction : null" >Destinatario</x-table.heading>
                <x-table.heading >Asignado a</x-table.heading>

                @if(!auth()->user()->hasRole('Oficialia de partes'))

                    <x-table.heading sortable wire:click="sortBy('fecha_termino')" :direction="$sort === 'fecha_termino' ? $direction : null" >Fecha de termino</x-table.heading>
                    <x-table.heading >Estado</x-table.heading>

                @endif

                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($entradas as $entrada)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $entrada->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio</span>

                            {{ $entrada->folio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de oficio</span>

                            {{ $entrada->numero_oficio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Asunto</span>

                            {!! $entrada->asunto !!}

                        </x-table.cell>

                        <x-table.cell>

                            <span clss="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Origen</span>

                            {{ $entrada->origen->name }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Destinatario</span>

                            {{ $entrada->destino->name }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Asgignado a</span>

                            <ul class="list-disc">

                                @foreach ($entrada->asignadoA as $asignado)

                                <li>

                                    <div class="flex space-x-3 items-center">

                                        {{ $asignado->name }}

                                    </div>

                                </li>

                                @endforeach

                            </ul>

                        </x-table.cell>

                        @if(!auth()->user()->hasRole('Oficialia de partes'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de termino</span>

                                @if( now()->diffInDays($entrada->fecha_termino) <= 5 )
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs whitespace-nowrap">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>
                                @elseif( now() > $entrada->fecha_termino)
                                    <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs whitespace-nowrap">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>
                                @elseif( now()->diffInDays($entrada->fecha_termino) <= 15 )
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs whitespace-nowrap">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>
                                @elseif( now()->diffInDays($entrada->fecha_termino) > 15 )
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs whitespace-nowrap">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>
                                @endif

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                                @if($entrada->seguimientos_count == 0 && $entrada->conclusiones_count == 0)
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs">Nueva</span>
                                @elseif($entrada->seguimientos_count != 0 && $entrada->conclusiones_count == 0)
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Seguimiento</span>
                                @elseif($entrada->seguimientos_count != 0 || $entrada->conclusiones_count != 0)
                                    <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs">Concluida</span>
                                @endif

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>


                            <span class="font-semibold">@if($entrada->creadoPor != null)Registrado por: {{$entrada->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{ $entrada->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($entrada->actualizadoPor != null)Actualizado por: {{$entrada->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $entrada->updated_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                            <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                <div>

                                    <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>

                                    </button>

                                </div>

                                <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                    @can('Ver entrada')

                                        <a
                                            href="{{ route('entrada', $entrada->id ) }}"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Ver
                                        </a>

                                    @endcan

                                    @can('Editar entrada')

                                        <button
                                            wire:click="abrirModalEditar({{ $entrada->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Editar
                                        </button>

                                    @endcan

                                    @can('Borrar entrada')

                                        <button
                                            wire:click="abrirModalBorrar({{ $entrada->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Eliminar
                                        </button>

                                    @endcan

                                </div>

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="11">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="11" class="bg-gray-50">

                        {{ $entradas->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nueva entrada
            @elseif($editar)
                Editar entrada
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.numero_oficio" label="Número de oficio" :error="$errors->first('modelo_editar.numero_oficio')" class="w-full">

                    <x-input-text id="modelo_editar.numero_oficio" wire:model="modelo_editar.numero_oficio" />

                </x-input-group>

                <x-input-group for="modelo_editar.date_for_editing" label="Fecha de término" :error="$errors->first('modelo_editar.date_for_editing')" class="w-full">

                    <x-input-date id="modelo_editar.date_for_editing" wire:model.live="modelo_editar.date_for_editing" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.dependencia_id" label="Origen" :error="$errors->first('modelo_editar.dependencia_id')" class="w-full">

                    <x-input-select id="modelo_editar.dependencia_id" wire:model="modelo_editar.dependencia_id" class="">

                        <option value="">Seleccione una opción</option>

                        @foreach ($dependencias as $dependencia)

                            <option value="{{ $dependencia->id }}">{{ $dependencia->name }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="modelo_editar.destinatario" label="Destinatario" :error="$errors->first('modelo_editar.destinatario')" class="w-full">

                    <x-input-select id="modelo_editar.destinatario" wire:model="modelo_editar.destinatario" class="">

                        <option value="">Seleccione una opción</option>

                        @foreach ($oficinas as $oficina)

                            <option value="{{ $oficina->id }}">{{ $oficina->name }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="asignados" label="Asignar a" :error="$errors->first('asignados')" class="w-full">

                    <div x-data = "{ model: @entangle('asignados') }"
                        x-init =
                        "
                            select2 = $($refs.select)
                                .select2({
                                    placeholder: 'Asignar a',
                                    width: '100%',
                                })

                            select2.on('change', function(){
                                $wire.set('asignados', $(this).val())
                            })

                            select2.on('keyup', function(e) {
                                if (e.keyCode === 13){
                                    $wire.set('asignados', $('.select2').val())
                                }
                            });

                            $watch('model', (value) => {
                                select2.val(value).trigger('change');
                            });
                        "
                        wire:ignore>

                        <select class="bg-white rounded text-sm w-full z-50"
                                wire:model.live="asignados"
                                x-ref="select"
                                multiple="multiple">

                            @foreach ($usuarios as $user)

                                <option value="{{ $user->id }}">{{ $user->name }}</option>

                            @endforeach

                        </select>

                    </div>

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.asunto" label="Asunto" :error="$errors->first('modelo_editar.asunto')" class="w-full">

                    <x-input-text-area wire:model="modelo_editar.asunto" />

                </x-input-group>

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
                                    href="{{ $file->getLink() }}"
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
            Eliminar entrada
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar la entrada? No sera posible recuperar la información.
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

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush

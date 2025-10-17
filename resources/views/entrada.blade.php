@extends('layouts.admin')

@section('content')

    <x-header>Entrada <span class="text-base">(Folio: {{ $entrada->folio }})</span></x-header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-5">

        <div class="bg-white rounded-xl grid grid-cols-1 lg:grid-cols-2 gap-3 p-4">

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Númemro de oficio:</strong> {{ $entrada->numero_oficio }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Fecha de término:</strong> {{ $entrada->fecha_termino }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Origen:</strong> {{ $entrada->origen->name }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Destinatario:</strong> {{ $entrada->destino->name }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Registrada en:</strong> {{ $entrada->created_at }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <strong>Asignada a:</strong>

                <ul class="list-disc px-5">

                    @foreach ($entrada->asignadoA as $asignado)

                    <li>

                        <div class="flex space-x-3 items-center">

                            {{ $asignado->name }}

                        </div>

                    </li>

                    @endforeach

                </ul>

            </div>

        </div>

        <div class="bg-white rounded-xl p-4 flex flex-col justify-between space-y-2">

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Asunto:</strong> {!! $entrada->asunto !!}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-2 px-2">

                <div class="flex flex-row flex-wrap gap-2 items-center justify-end">

                    @foreach ($entrada->files as $file)

                            <div class="flex gap-2 bg-red-200 rounded-full p-1">

                                <a
                                    href="{{ $file->getLink() }}"
                                    target="_blank"
                                    class="bg-red-400 hover:shadow-lg text-white text-xs px-3 py-1 rounded-full hover:bg-red-700 focus:outline-none w-auto"
                                >
                                PDF {{ $loop->iteration }}
                                </a>

                            </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

    <x-header>Seguimientos</x-header>

    <div class="space-y-3">

        @foreach ($entrada->seguimientos as $seguimiento)

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

                <div class="bg-white rounded-xl grid grid-cols-1 lg:grid-cols-2 gap-3 p-4">

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Oficio de respuesta:</strong> {{ $seguimiento->oficio_respuesta }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Fecha de respuesta:</strong> {{ $seguimiento->fecha_respuesta }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Registrada por:</strong> {{ $seguimiento->creadoPor->name }}, en {{ $seguimiento->created_at }}</p>

                    </div>

                </div>

                <div class="bg-white rounded-xl p-4 flex flex-col justify-between space-y-2">

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Comentario:</strong> {!! $seguimiento->comentario !!}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-2 px-2">

                        <div class="flex flex-row flex-wrap gap-2 items-center justify-end">

                            @foreach ($seguimiento->files as $file)

                                    <div class="flex gap-2 bg-red-200 rounded-full p-1">

                                        <a
                                            href="{{ $file->getLink() }}"
                                            target="_blank"
                                            class="bg-red-400 hover:shadow-lg text-white text-xs px-3 py-1 rounded-full hover:bg-red-700 focus:outline-none w-auto"
                                        >
                                        PDF {{ $loop->iteration }}
                                        </a>

                                    </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            @if (!$loop->last)
                <hr>
            @endif

        @endforeach

    </div>

    <x-header class="mt-5">Conclusiones</x-header>

    <div class="space-y-3">

        @foreach ($entrada->conclusiones as $conclusion)

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

                <div class="bg-white rounded-xl p-4">

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Registrada por:</strong> {{ $conclusion->creadoPor->name }}, en {{ $conclusion->created_at }}</p>

                    </div>

                </div>

                <div class="bg-white rounded-xl p-4 flex flex-col justify-between space-y-2">

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Comentario:</strong> {!! $conclusion->comentario !!}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-2 px-2">

                        <div class="flex flex-row flex-wrap gap-2 items-center justify-end">

                            @foreach ($conclusion->files as $file)

                                    <div class="flex gap-2 bg-red-200 rounded-full p-1">

                                        <a
                                            href="{{ $file->getLink() }}"
                                            target="_blank"
                                            class="bg-red-400 hover:shadow-lg text-white text-xs px-3 py-1 rounded-full hover:bg-red-700 focus:outline-none w-auto"
                                        >
                                        PDF {{ $loop->iteration }}
                                        </a>

                                    </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            @if (!$loop->last)
                <hr>
            @endif

        @endforeach

    </div>

@endsection

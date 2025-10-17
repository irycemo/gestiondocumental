@extends('layouts.admin')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

    <div class=" border-t-4 border-blue-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white">

        <div class="flex  mb-2 ">

            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">

                <h5 class="text-blueGray-400 uppercase font-semibold text-lg  tracking-widest ">Entradas</h5>

                <span class="font-bold text-2xl text-blueGray-600">
                    <span>
                        {{ $entries_count }}
                    </span>

                </span>

            </div>

            <div class="relative w-auto pl-4 flex-initial overflow-hidden">

                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-500">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                      </svg>

                </div>

            </div>

        </div>

    </div>

    <div class=" border-t-4 border-green-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white">

        <div class="flex  mb-2">

            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">

                <h5 class="text-blueGray-400 uppercase font-semibold text-lg  tracking-widest ">Seguimientos</h5>

                <span class="font-bold text-2xl text-blueGray-600">
                    <span>
                        {{ $trackings_count }}
                    </span>

                </span>

            </div>

            <div class="relative w-auto pl-4 flex-initial overflow-hidden">

                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-500">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                    </svg>

                </div>

            </div>

        </div>

    </div>

    <div class=" border-t-4 border-gray-400  p-4 shadow-xl text-gray-600 rounded-xl bg-white">

        <div class="flex mb-2">

            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">

                <h5 class="text-blueGray-400 uppercase font-semibold text-lg  tracking-widest ">Conclusiones</h5>

                <span class="font-bold text-2xl text-blueGray-600">
                    <span>
                       {{ $conclusions_count }}
                    </span>

                </span>

            </div>

            <div class="relative w-auto pl-4 flex-initial overflow-hidden">

                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-500">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

    <div>

        <h2 class="text-2xl tracking-widest px-6 py-3 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-3 cursor-pointer  bg-white">Entradas</h2>

        <div class="p-4 bg-white rounded-2xl divide-y mb-3">

            @forelse ($entries as $entrie)

                <div class="flex-col justify-between items-center py-3 lg:space-x-4 space-y-3 lg:space-y-0">

                    <div class=" bg-gray-100 rounded-lg w-full">

                        <div class="py-1 px-2">

                            <p><strong>Orignen:</strong> {{ $entrie->origen->name }}</p>

                        </div>

                        <div class="py-1 px-2">

                            <p>
                                <strong>Fecha de termino:</strong>
                                @if( now()->diffInDays($entrie->fecha_termino) <= 5 )
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">{{ $entrie->fecha_termino->format('d-m-Y') }}</span>
                                @elseif( now() > $entrie->fecha_termino)
                                    <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs">{{ $entrie->fecha_termino->format('d-m-Y') }}</span>
                                @elseif( now()->diffInDays($entrie->fecha_termino) <= 15 )
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">{{ $entrie->fecha_termino->format('d-m-Y') }}</span>
                                @elseif( now()->diffInDays($entrie->fecha_termino) > 15 )
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">{{ $entrie->fecha_termino->format('d-m-Y') }}</span>
                                @endif
                            </p>

                        </div>

                    </div>

                    <x-link-green href="{{ route('entrada', $entrie->id) }}" >Ver</x-link-green>

                </div>

            @empty

                <div class="text-center p-4 text-xl">
                    <p>No hay entradas</p>
                </div>

            @endforelse

        </div>

        <x-link-blue href="{{ route('entradas') }}">Ver todas</x-link-blue>

    </div>

    <div>

        <h2 class="text-2xl tracking-widest px-6 py-3 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-3 cursor-pointer  bg-white">Seguimientos</h2>

        <div class="p-4 bg-white rounded-2xl divide-y mb-3">

            @forelse ($trackings as $tracking)

                <div class="flex justify-between items-center py-3 space-x-4">

                    <div class=" bg-gray-100 rounded-lg w-full">

                        <div class="py-1 px-2">

                            <p><strong>Oficio de entrada:</strong> {{ $tracking->entrada->numero_oficio }}</p>

                        </div>

                        <div class="py-1 px-2">

                            <p><strong>Fecha de respuesta:</strong> {{ $tracking->fecha_respuesta }}</p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center p-4 text-xl">
                    <p>No hay entradas</p>
                </div>

            @endforelse

        </div>

        <x-link-blue href="{{ route('seguimientos') }}">Ver todos</x-link-blue>

    </div>

    <div>

        <h2 class="text-2xl tracking-widest px-6 py-3 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-3 cursor-pointer  bg-white">Conclusiones</h2>

        <div class="p-4 bg-white rounded-2xl divide-y mb-3">

            @forelse ($conclusions as $conclusion)

                <div class="flex justify-between items-center py-3 space-x-4">

                    <div class=" bg-gray-100 rounded-lg w-full">

                        <div class="py-1 px-2">

                            <p><strong>Oficio de entrada:</strong> {{  $conclusion->entrada->numero_oficio }}</p>

                        </div>

                        <div class="py-1 px-2">

                            <p><strong>Fecha de registro:</strong> {{  $conclusion->created_at }}</p>

                        </div>

                    </div>


                </div>

            @empty

                <div class="text-center p-4 text-xl">
                    <p>No hay entradas</p>
                </div>

            @endforelse

        </div>

        <x-link-blue href="{{ route('conclusiones') }}">Ver todas</x-link-blue>

    </div>

</div>

@endsection

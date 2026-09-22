@extends('layouts.admin')

@section('content')

    <x-header>Últimas entradas y su seguimiento</x-header>

    <div class="grid grid-cols-2 gap-4 w-2/3 mx-auto">

        @forelse ($entries as $entrada)

            @php
                $concluida = $entrada->conclusiones->isNotEmpty();
                $en_seguimiento = $entrada->seguimientos->isNotEmpty();
                $vencida = $entrada->fecha_termino && $entrada->fecha_termino->isPast() && ! $concluida;
                $ultimo_seguimiento = $entrada->seguimientos->first();
                $ultima_conclusion = $entrada->conclusiones->first();
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-sm">

                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">

                    <span class="bg-gray-700 dark:bg-gray-400 text-white dark:text-gray-800 px-3 py-1 rounded-full">Folio {{ $entrada->folio }}</span>

                    @if ($concluida)
                        <span class="bg-green-500 text-white text-xs px-3 rounded-full">Concluida</span>
                    @elseif ($vencida)
                        <span class="bg-red-500 text-white text-xs px-3 rounded-full">Vencida</span>
                    @elseif ($en_seguimiento)
                        <span class="bg-blue-500 text-white text-xs px-3 rounded-full">En seguimiento</span>
                    @else
                        <span class="bg-gray-400 text-white text-xs px-3 rounded-full">Sin seguimiento</span>
                    @endif

                </div>

                <div class="space-y-4 mb-4">

                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 space-y-1">

                        <p><strong>Asunto:</strong> {{ $entrada->limit }}</p>

                        <p><strong>Origen:</strong> {{ $entrada->origen?->name ?? 'Sin registrar' }}</p>

                        <p><strong>Destinatario:</strong> {{ $entrada->destino?->name ?? 'Sin registrar' }}</p>

                        <p><strong>Asignada a:</strong> {{ $entrada->asignadoA->isNotEmpty() ? $entrada->asignadoA->pluck('name')->implode(', ') : 'Sin asignar' }}</p>

                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 space-y-1">

                        <p>

                            <strong>Fecha de término:</strong>

                            @if ($entrada->fecha_termino)

                                @if ($entrada->fecha_termino->isPast())

                                    <span class="bg-gray-500 text-white px-2  rounded-full text-xs">Vencida · {{ $entrada->fecha_termino->format('d-m-Y') }}</span>

                                @elseif ($entrada->fecha_termino->diffInDays() <= 5)

                                    <span class="bg-red-500 text-white px-2  rounded-full text-xs">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>

                                @elseif ($entrada->fecha_termino->diffInDays() <= 15)

                                    <span class="bg-yellow-500 text-white px-2  rounded-full text-xs">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>

                                @else

                                    <span class="bg-green-500 text-white px-2  rounded-full text-xs">{{ $entrada->fecha_termino->format('d-m-Y') }}</span>

                                @endif

                            @else

                                <span class="text-gray-400">Sin fecha</span>

                            @endif

                        </p>

                        <p><strong>Registrada:</strong> {{ $entrada->created_at }}</p>

                    </div>

                </div>

                <div class="space-y-4 mb-4">

                    <div class="rounded-lg border border-blue-200 dark:border-blue-900 bg-blue-50 dark:bg-blue-950 p-3">

                        <div class="flex items-center justify-between mb-2">

                            <h6 class="font-semibold text-blue-800 dark:text-blue-300 flex items-center gap-2">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                </svg>

                                Seguimientos

                            </h6>

                            <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $entrada->seguimientos->count() }}</span>

                        </div>

                        @if ($ultimo_seguimiento)

                            <p><strong>Último oficio de respuesta:</strong> {{ $ultimo_seguimiento->oficio_respuesta }}</p>

                            <p><strong>Fecha de respuesta:</strong> {{ $ultimo_seguimiento->fecha_respuesta?->format('d-m-Y') }}</p>

                        @else

                            <p class="text-gray-500 dark:text-gray-400">Sin seguimientos registrados</p>

                        @endif

                    </div>

                    <div class="rounded-lg border border-green-200 dark:border-green-900 bg-green-50 dark:bg-green-950 p-3">

                        <div class="flex items-center justify-between mb-2">

                            <h6 class="font-semibold text-green-800 dark:text-green-300 flex items-center gap-2">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                </svg>

                                Conclusiones

                            </h6>

                            <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $entrada->conclusiones->count() }}</span>

                        </div>

                        @if ($ultima_conclusion)

                            <p><strong>Registrada por:</strong> {{ $ultima_conclusion->creadoPor?->name ?? 'Sin registrar' }}</p>

                            <p><strong>Fecha de registro:</strong> {{ $ultima_conclusion->created_at }}</p>

                        @else

                            <p class="text-gray-500 dark:text-gray-400">Sin conclusiones registradas</p>

                        @endif

                    </div>

                </div>

                <x-link-blue  href="{{ route('entrada', $entrada->id) }}">Ver</x-link-blue>

            </div>

        @empty

            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 text-center text-gray-500 dark:text-gray-400">

                <p class="text-xl">No hay entradas registradas</p>

            </div>

        @endforelse

    </div>

@endsection

<div>

    @if($group)

        <div
            x-data="{ open_phase: false, open_task: false, open_group: false, open_drop_down:false, open_users:false }"
            @keydown.escape.window="open_phase = false, open_task = false"
            @close-phase-modal.window="open_phase = false"
            @close-task-modal.window="open_task = false"
            @open-task-modal.window="open_task = true"
            class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">

            <div class="max-w-6xl mx-auto px-4 mb-5 text-sm space-y-4">

                <div class="flex justify-between items-center">

                    <div class="relative">

                        <span x-on:click="open_drop_down=true" class="px-2 py-0.5 text-gray-500 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer border border-gray-500 dark:border-gray-600 rounded-full">
                            {{ __("Select a group") }}
                        </select>

                        <div x-cloak x-show="open_drop_down" x-on:click.away="open_drop_down=false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                            @foreach ($groups as $group_item)

                                <div class="flex items-center justify-between">

                                    <a href="{{ route('kanban-board', $group_item) }}" class="w-full rounded-lg mx-1 block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:text-gray-700 dark:hover:bg-gray-100" role="menuitem">{{ $group_item->name }}</a>

                                </div>

                            @endforeach

                        </div>

                    </div>

                    <div>

                        <button
                            @click="open_group = ! open_group"
                            class="px-2 py-0.5 text-gray-500 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer border border-gray-500 dark:border-gray-600 rounded-full">

                            {{ __('Create a new group') }}

                        </button>

                    </div>

                </div>

                <hr>

            </div>

            {{-- Header --}}
            <div class="max-w-6xl mx-auto px-4">

                <div class="flex justify-between items-center mb-6">

                    <div class="flex gap-4 items-center">

                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $group->name }}</h1>

                        @if($group->created_by == auth()->id())

                            <button
                                @click="open_users = ! open_users"
                                class="px-2 py-0.5 text-gray-500  hover:bg-gray-300 hover:text-gray-700 dark:text-gray-200 transition-colors cursor-pointer border border-gray-500 dark:hover:bg-gray-700 rounded-full mr-2" title="Agregar participantes">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>

                            </button>

                        @endif

                    </div>

                    <button
                        @click="open_phase = ! open_phase"
                        class="px-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors cursor-pointer">

                        {{ __("Add phase") }}

                    </button>

                </div>

                <div
                    wire:sort="handleSortGroup"
                    wire:sort:group="phases"
                    wire:sort:group-id="grup_id"
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 items-start gap-6">

                    @foreach ($group->phases->sortBy('order') as $phase)

                        <div
                            wire:sort:item="{{ $phase->id }}"
                            class="bg-gray-200/60 dark:bg-gray-800 rounded-xl p-4 cursor-grab active:cursor-grabbing group-[status]:">

                            {{-- Column header --}}
                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-2.5 h-2.5 rounded-full bg-{{ $phase->color }}-500"></span>

                                <h2 class="font-semibold text-gray-700 dark:text-gray-200">{{ $phase->name }}</h2>

                                <span class="ml-auto text-sm text-gray-500 dark:text-gray-400 bg-white/70 dark:bg-gray-700 rounded-full px-2 py-0.5">{{ $phase->tasks->count() }}</span>

                            </div>

                            {{-- Task list --}}
                            <ul
                                wire:sort="handleSort"
                                wire:sort:group="tasks"
                                wire:sort:group-id="{{ $phase->id }}"
                                class="space-y-2 min-h-15">

                                @forelse ($phase->tasks->sortBy('position') as $task)

                                    <li
                                        wire:key="task-{{ $task->id }}"
                                        wire:sort:item="{{ $task->id }}"
                                        class="bg-white dark:bg-gray-800 rounded-lg border border- p-3 shadow-sm cursor-grab active:cursor-grabbing group">

                                        <div class="flex items-start justify-between gap-2">

                                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $task->title }}</span>

                                            <div class="flex items-center gap-2">

                                                <button
                                                    wire:click="openEditTaskModal({{ $task->id }})"
                                                    wire:sort:ignore
                                                    title="Ver / Editar"
                                                    class="cursor-pointer text-gray-400 hover:text-blue-500 transition-colors shrink-0 opacity-0 group-hover:opacity-100 in-[.sorting]:opacity-0!">

                                                    <div class="block">

                                                        <x-icon.pencil />

                                                    </div>

                                                </button>

                                                <button
                                                    wire:click="deleteTask({{ $task->id }})"
                                                    wire:sort:ignore
                                                    wire:confirm="Are you sure you want to delete the task?"
                                                    title="Eliminar"
                                                    class="cursor-pointer text-gray-400 hover:text-red-500 transition-colors shrink-0 opacity-0 group-hover:opacity-100 in-[.sorting]:opacity-0!">

                                                    <x-icon.close />

                                                </button>

                                            </div>

                                        </div>

                                    </li>

                                @empty

                                    <li class="text-sm text-gray-400 text-center py-4">{{ __("No tasks") }}</li>

                                @endforelse

                            </ul>

                            {{-- Add task --}}
                            <div class="mt-3">

                                <button
                                    wire:click="openCreateTaskModal({{ $phase->id }})"
                                    class="w-full text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 flex items-center gap-1 py-1.5 transition-colors cursor-pointer">

                                    <x-icon.plus />

                                    {{ __("Add task") }}

                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- Group modal --}}
            <div
                x-cloak
                x-show="open_group"
                x-transition.opacity
                class="fixed inset-0 z-50">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50"></div>

                <!-- Modal Content -->
                <div class="fixed inset-0 overflow-y-auto">

                    <div class="flex min-h-full items-center justify-center p-4">

                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full max-h-[85vh] flex flex-col">

                            <!-- Header -->
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0">

                                <button
                                    @click="open_group = false"
                                    class="absolute top-3.5 right-3.5 text-gray-400  hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                                >
                                    <x-icon.close />
                                </button>

                                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __("Create Group") }}</h2>

                            </div>

                            <!-- Content Area - Scrollable -->
                            <div class="flex-1 overflow-y-auto px-5 py-5">

                                <div wire:transition="form" class="space-y-4">

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Group Name") }}</label>

                                        <input
                                            type="text"
                                            wire:model.blur="group_name"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="e.g., Tailwind UI Kit"
                                        >

                                        @error('group_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Group Area") }}</label>

                                        <select
                                            wire:model.change="group_area"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="">{{ __("Select an category...") }}</option>

                                            @foreach ($areas as $area)

                                                <option value="{{ $area->value }}" class="capitalize">{{ $area->label() }}</option>

                                            @endforeach

                                        </select>

                                        @error('group_area') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="px-5 py-3.5 border-t border-gray-200 dark:border-gray-700 shrink-0 flex justify-between">

                                <div class="flex gap-2 ml-auto">

                                    <button
                                        @click="open_group = false"
                                        class="px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 cursor-pointer"
                                    >
                                        {{__ ("Cancel")}}
                                    </button>

                                    <button
                                        wire:click="saveGroup"
                                        class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700 data-loading:opacity-50 data-loading:pointer-events-none"
                                    >
                                        {{ __("Submit Group") }}
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Phase modal --}}
            <div
                x-cloak
                x-show="open_phase"
                x-transition.opacity
                class="fixed inset-0 z-50">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50"></div>

                <!-- Modal Content -->
                <div class="fixed inset-0 overflow-y-auto">

                    <div class="flex min-h-full items-center justify-center p-4">

                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full max-h-[85vh] flex flex-col">

                            <!-- Header -->
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0">

                                <button
                                    @click="open_phase = false"
                                    class="absolute top-3.5 right-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                                >
                                    <x-icon.close />
                                </button>

                                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __("Create Phase") }}</h2>

                            </div>

                            <!-- Content Area - Scrollable -->
                            <div class="flex-1 overflow-y-auto px-5 py-5">

                                <div wire:transition="form" class="space-y-4">

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Phase Name") }}</label>

                                        <input
                                            type="text"
                                            wire:model.blur="phase_name"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="e.g., Tailwind UI Kit"
                                        >

                                        @error('phase_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Phase Order") }}</label>

                                        <input
                                            type="number"
                                            wire:model.blur="phase_order"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="e.g., Tailwind UI Kit"
                                        >

                                        @error('phase_order') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 ark:text-gray-300 mb-1">{{ __("Phase Color") }}</label>

                                        <select
                                            wire:model.change="phase_color"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 border dark:text-gray-200 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="">{{ __("Select a color...") }}</option>

                                            @foreach ($colors as $color)

                                                <option value="{{ $color->value }}" class="capitalize">{{ $color }}</option>

                                            @endforeach

                                        </select>

                                        @error('phase_color') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="px-5 py-3.5 border-t border-gray-200 dark:border-gray-700 shrink-0 flex justify-between">

                                <div class="flex gap-2 ml-auto">

                                    <button
                                        @click="open_phase = false"
                                        class="px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 cursor-pointer"
                                    >
                                        {{__ ("Cancel")}}
                                    </button>

                                    <button
                                        wire:click="savePhase"
                                        class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700 data-loading:opacity-50 data-loading:pointer-events-none"
                                    >
                                        {{ __("Submit Phase") }}
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Task modal --}}
            <div
                x-cloak
                x-show="open_task"
                x-transition.opacity
                class="fixed inset-0 z-50">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50"></div>

                <!-- Modal Content -->
                <div class="fixed inset-0 overflow-y-auto">

                    <div class="flex min-h-full items-center justify-center p-4">

                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full max-h-[85vh] flex flex-col">

                            <!-- Header -->
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800 shrink-0">

                                <button
                                    @click="open_task = false"
                                    class="absolute top-3.5 right-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                                >
                                    <x-icon.close />
                                </button>

                                @if($mode === 'create')

                                    <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __("Create Task") }}</h2>

                                @elseif($mode === 'edit')

                                    <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __("Update Task") }}</h2>

                                @endif

                            </div>

                            <!-- Content Area - Scrollable -->
                            <div class="flex-1 overflow-y-auto px-5 py-5">

                                <div wire:transition="form" class="space-y-4">

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Task Name") }}</label>

                                        <input
                                            type="text"
                                            wire:model.blur="task_title"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="e.g., Tailwind UI Kit"
                                        >

                                        @error('task_title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Task Description") }}</label>

                                        <textarea
                                            x-data="{
                                                resize(){
                                                    this.$el.style.height = 'auto';
                                                    this.$el.style.height = this.$el.scrollHeight + 'px';
                                                }
                                            }"
                                            x-init="resize()"
                                            @input="resize()"
                                            wire.ignore.self
                                            rows="10"
                                            class="border border-gray-200  dark:border-gray-600 rounded-lg mt-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-relaxed px-2.5 py-1.5 min-h-40 w-full focus:outline-none focus:ring-0 resize-none field-sizing-content"
                                            placeholder="Click here to add notes"
                                            wire:model="task_description">
                                        </textarea>

                                        @error('task_description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                    <div class=" text-xs text-gray-700 dark:text-gray-400">

                                        <div>
                                            <strong>{{ __("Created by") }}:</strong> {{ $task_selected?->createdBy?->name }}, {{ $task_selected?->created_at_formatted }}
                                        </div>

                                        <div>
                                            <strong>{{ __("Updated by") }}:</strong> {{ $task_selected?->updatedBy?->name }}, {{ $task_selected?->updated_at_formatted }}
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="px-5 py-3.5 border-t border-gray-200 dark:border-gray-700 shrink-0 flex justify-between">

                                <div class="flex gap-2 ml-auto">

                                    <button
                                        @click="open_task = false"
                                        class="px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 cursor-pointer"
                                    >
                                        {{__ ("Cancel")}}
                                    </button>

                                    @if($mode === 'create')

                                        <button
                                            wire:click="saveTask"
                                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700 data-loading:opacity-50 data-loading:pointer-events-none"
                                        >
                                            {{ __("Submit Task") }}
                                        </button>

                                    @elseif($mode === 'edit')

                                        <button
                                            wire:click="updateTask"
                                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700 data-loading:opacity-50 data-loading:pointer-events-none"
                                        >
                                            {{ __("Update Task") }}
                                        </button>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Add users modal --}}
            <div
                x-cloak
                x-show="open_users"
                x-transition.opacity
                class="fixed inset-0 z-50">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50"></div>

                <!-- Modal Content -->
                <div class="fixed inset-0 overflow-y-auto">

                    <div class="flex min-h-full items-center justify-center p-4">

                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full max-h-[85vh] flex flex-col">

                            <!-- Header -->
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800 shrink-0">

                                <button
                                    @click="open_users = false"
                                    class="absolute top-3.5 right-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                                >
                                    <x-icon.close />
                                </button>

                                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __("Add users") }}</h2>

                            </div>

                            <!-- Content Area - Scrollable -->
                            <div class="flex-1 overflow-y-auto px-5 py-5">

                                <div wire:transition="form" class="space-y-4">

                                    <div>

                                        <label class="block text-xs font-medium text-gray-700 ark:text-gray-300 mb-1">{{ __("Users") }}</label>

                                        <select
                                            wire:model.change="phase_color"
                                            wire:model.live="user_id"
                                            class="w-full px-2.5 py-1.5 text-sm text-gray-700 border dark:text-gray-200 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="">{{ __("Select an user...") }}</option>

                                            @foreach ($users as $user_item)

                                                <option value="{{ $user_item->id }}" >{{ $user_item->name }}</option>

                                            @endforeach

                                        </select>

                                        @error('phase_color') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                                <div class="flex flex-wrap gap-4 p-2 mt-3">

                                    @foreach ($group->users as $user)

                                        <div class="flex gap-4 text-xs border rounded-full py-1 px-2">

                                            <span class="whitespace-nowrap">{{ $user->name }}</span>

                                            <button
                                                wire:click="deleteUser({{ $user->id }})"
                                                wire:loading.attr="disabled">

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>

                                            </button>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="px-5 py-3.5 border-t border-gray-200 dark:border-gray-700 shrink-0 flex justify-between">

                                <div class="flex gap-2 ml-auto">

                                    <button
                                        @click="open_users = false"
                                        class="px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 cursor-pointer"
                                    >
                                        {{__ ("Cancel")}}
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @else

        <div
            x-data="{ open_grupo: false }"
            class="py-12 text-center font-thin space-y-6">

            <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{__("You have no groups") }}</span>

            <div>

                <button
                    @click="open_grupo = ! open_grupo"
                    class="px-2 py-0.5 text-gray-500 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 transition-colors cursor-pointer border border-gray-500 dark:border-gray-600 rounded-full">

                    {{ __("Create a new group") }}

                </button>

            </div>

            <div
                x-show="open_grupo"
                class="flex min-h-full items-center justify-center p-4">

                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full max-h-[85vh] flex flex-col">

                    <!-- Header -->
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0">

                        <button
                            @click="open_group = false"
                            class="absolute top-3.5 right-3.5 text-gray-400  hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                        >
                            <x-icon.close />
                        </button>

                        <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __("Create Group") }}</h2>

                    </div>

                    <!-- Content Area - Scrollable -->
                    <div class="flex-1 overflow-y-auto px-5 py-5">

                        <div wire:transition="form" class="space-y-4">

                            <div>

                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Group Name") }}</label>

                                <input
                                    type="text"
                                    wire:model.blur="group_name"
                                    class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="e.g., Tailwind UI Kit"
                                >

                                @error('group_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                            </div>

                            <div>

                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __("Group Area") }}</label>

                                <select
                                    wire:model.change="group_area"
                                    class="w-full px-2.5 py-1.5 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">{{ __("Select a area...") }}</option>

                                    @foreach ($areas as $area)

                                        <option value="{{ $area->value }}" class="capitalize">{{ $area->label() }}</option>

                                    @endforeach

                                </select>

                                @error('group_area') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror

                            </div>

                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="px-5 py-3.5 border-t border-gray-200 dark:border-gray-700 shrink-0 flex justify-between">

                        <div class="flex gap-2 ml-auto">

                            <button
                                @click="open_group = false"
                                class="px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 cursor-pointer"
                            >
                                {{ __("Cancel") }}
                            </button>

                            <button
                                wire:click="saveGroup"
                                class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700 data-loading:opacity-50 data-loading:pointer-events-none"
                            >
                                {{ __("Submit Group") }}
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>

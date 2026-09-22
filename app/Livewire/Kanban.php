<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Group;
use App\Models\Phase;
use App\Models\Task;
use Illuminate\Validation\Rule;
use App\Enums\AreaEnum;
use App\Enums\ColorEnum;
use App\Models\GroupUser;

class Kanban extends Component
{

    public Group $group;
    public Task $task_selected;

    public string $phase_name = '';
    public int $phase_order = 0;
    public string $phase_color = '';

    public string $task_title = '';
    public string $task_description = '';

    public string $group_name = '';
    public string $group_area = '';

    public array $areas;
    public array $colors;
    public int $phase_id;
    public string $mode = '';
    public $users;
    public int $user_id;
    public $groups;

    public function mount(){

        if(isset($this->group)){

            $this->group->load('phases.tasks', 'users');

        }else{

            if(auth()->user()->groups->first()){

                $this->group = auth()->user()->groups->first();

            }else{

                $this->group = Group::make();

            }

            $this->group->load('phases.tasks', 'users');

        }

        $this->groups = auth()->user()->groups;

        $group_ids = GroupUser::where('user_id', auth()->id())->pluck('group_id');

        $added_groups = Group::whereIn('id', $group_ids)->get();

        if($added_groups->count()){

            $this->groups = $this->groups->merge($added_groups);

        }

        $this->colors = ColorEnum::cases();

        $this->areas = AreaEnum::cases();

        $this->users = User::select('id', 'name')->orderBy('name')->get();

    }

    public function handleSort(int|string $id, string $position, int|string $phase_id):void
    {

        $task = Task::findOrFail($id);

        $old_phase_id = $task->phase_id;

        $task->update(['phase_id' => $phase_id]);

        /* Get all siblings in the same column (excluding the moved task) */
        $siblings = Task::where('phase_id', $phase_id)
                            ->where('id', '!=', $id)
                            ->orderBy('position')
                            ->pluck('id')
                            ->all();

        /* Insert at the correct position */
        array_splice($siblings, $position, 0, [$task->id]);

        /* Update all positions */
        foreach ($siblings as $index => $taskId) {

            Task::where('id', $taskId)->update(['position' => $index]);

        }

        if($old_phase_id != $phase_id){

            $this->reorder($old_phase_id);

        }

        $this->group->load('phases.tasks', 'users');

    }

    public function handleSortGroup(int|string $id, string $position, int|string $group_id): void
    {

        $phase = Phase::findOrFail($id);

        /* Get all siblings in the same column (excluding the moved task) */
        $siblings = Phase::where('id', '!=', $phase->id)
                            ->orderBy('order')
                            ->pluck('id')
                            ->all();

        /* Insert at the correct position */
        array_splice($siblings, $position, 0, [$phase->id]);

        /* Update all positions */
        foreach ($siblings as $index => $phaseId) {

            Phase::where('id', $phaseId)->update(['order' => $index]);

        }

        $this->group->load('phases.tasks', 'users');

    }

    public function reorder(int $phase_id): void
    {

        Task::where('phase_id', $phase_id)
                ->orderBy('position')
                ->get()
                ->each(fn (Task $task, int $position) => $task->update(['position' => $position]));

    }

    public function deleteTask(int $taskId): void
    {

        $task = Task::findOrFail($taskId);

        $task->delete();

        $this->reorder($task->phase_id);

        $this->group->load('phases.tasks', 'users');

    }

    public function openCreateTaskModal(int $phase_id):void
    {

        $this->reset(['task_title', 'task_description']);

        $this->phase_id = $phase_id;

        $this->mode = 'create';

        $this->dispatch('open-task-modal');

    }

    public function openEditTaskModal(Task $task):void
    {

        $this->task_selected = $task;

        $this->task_title = $task->title;

        $this->task_description = $task->description;

        $this->mode = 'edit';

        $this->dispatch('open-task-modal');

    }

    public function savePhase():void
    {

        $this->validate([
            'phase_name' => 'required|string|min:3|max:255',
            'phase_order' => 'required|numeric|min:1',
            'phase_color' => ['required', 'string', Rule::in($this->colors)]
        ]);

        $phase_to_replace = $this->group->phases()->where('order', $this->phase_order)->first();

        if($phase_to_replace){

            $phases_to_reorder = $this->group->phases()->where('order', '>=', $this->phase_order)->get();

            foreach ($phases_to_reorder as $phase) {

                $phase->update(['order' => $phase->order + 1]);

            }

        }

        $this->group->phases()->create([
            'name' => trim($this->phase_name),
            'order' => $this->phase_order,
            'color' => $this->phase_color,
        ]);

        $this->group->load('phases.tasks', 'users');

        $this->dispatch('close-phase-modal');

        $this->reset(['phase_name', 'phase_order', 'phase_color']);

    }

    public function saveTask():void
    {

        $this->validate([
            'task_title' => 'required|string|min:3',
            'task_description' => 'nullable|string',
            'phase_id' => 'required|int'
        ]);

        Task::create([
            'title' => trim($this->task_title),
            'description' => trim($this->task_description),
            'position' => Task::max('position') + 1,
            'phase_id' => $this->phase_id,
            'created_by' => auth()->id()
        ]);

        $this->group->load('phases.tasks', 'users');

        $this->dispatch('close-task-modal');

        $this->reset(['task_title', 'task_description', 'phase_id']);

    }

    public function updateTask():void
    {

        $this->validate([
            'task_title' => 'required|string|min:3',
            'task_description' => 'nullable|string'
        ]);

        $this->task_selected->update([
            'title' => trim($this->task_title),
            'description' => trim($this->task_description),
            'updated_by' => auth()->id()
        ]);

        $this->group->load('phases.tasks', 'users');

        $this->dispatch('close-task-modal');

        $this->reset(['task_title', 'task_description', 'phase_id']);

    }

    public function saveGroup()
    {

        $this->validate([
            'group_name' => 'required|string|min:3',
            'group_area' => ['required', 'string', Rule::in($this->areas)],
        ]);

        $group = Group::create([
            'name' => trim($this->group_name),
            'area' => trim($this->group_area),
            'status' => 'active',
            'created_by' => auth()->id()
        ]);

        return redirect()->route('kanban-board', $group);

    }

    public function updatedUserId():void
    {

        $this->group->users()->attach($this->user_id);

        $this->group->load('users');

    }

    public function deleteUser($id){

        $this->group->users()->detach($id);

        $this->group->load('users');

    }

    public function render()
    {
        return view('livewire.kanban')->extends('layouts.admin');
    }

}

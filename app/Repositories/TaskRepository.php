<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\User;
use Spatie\Permission\Models\Role;

class TaskRepository implements TaskInterface
{
    private $role;
    public function getAgents(array $roles)
    {
        $this->role = $roles;
        return  User::whereHas("roles", function($q){ $q->whereIn("name", $this->role); })->get();
    }

    public function create(array $task)
    {
        return Task::create($task);
    }

    public function getTask($task_id)
    {
        return Task::findOrFail($task_id);
    }
}

<?php

namespace App\View\Components;

use App\Repositories\RepositoryInterface\TaskInterface;
use Illuminate\View\Component;

class TaskActionButton extends Component
{
    public $task;

    public $id;

    /**
     * Create a new component instance.
     *
     * @param TaskInterface $task
     * @param $id
     */
    public function __construct(
        TaskInterface $task,
        $id
    )
    {
        $this->id = $id;
        $this->task = $task;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $data = [
            'tasks' => $this->task->getTask($this->id)
        ];
        return view('components.task-action-button',$data);
    }
}

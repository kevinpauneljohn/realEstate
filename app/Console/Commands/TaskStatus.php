<?php

namespace App\Console\Commands;

use App\Repositories\RepositoryInterface\TaskInterface;
use Illuminate\Console\Command;

class TaskStatus extends Command
{
    private $task;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskStatus:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        parent::__construct();

        $this->task = $task;
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle(): string
    {
        return $this->task->updateTaskStatus();
//        return 0;
    }
}

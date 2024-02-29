<?php

namespace App\Exports;

use App\Task;
use App\Events\TaskEvent;
use App\Watcher;
use App\Repositories\RepositoryInterface\TaskInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class TaskExport implements FromCollection,WithHeadings,WithEvents
{
    protected $status;
    protected $type;

    function __construct($status, $type) {
        $this->status = $status;
        $this->type = $type;
    }

    public function headings():array
    {
        return [
            "Task #",
            "Due Date",
            "Title",
            "Priority",
            "Assigned To",
            "Creator",
            "Date Created",
            "Status"
        ];
    }

    public function collection()
    {
        $watcher = $this->getWatchedIds(auth()->user()->id);
        $data = [];
        foreach ($watcher as $watchers) {
            $data [] = $watchers['task_id'];
        }

        $get_task = '';
        if ($this->status == 'all') {
            if ($this->type == 'ticket') {
                $get_task = Task::all();
            } else if ($this->type == 'myticket') {
                $get_task = Task::all()->where('assigned_to', auth()->user()->id);
            } else if ($this->type == 'mywatched') {
                $get_task = Task::all()->whereIn('id', $data);
            }
        } else {
            if ($this->type == 'ticket') {
                $get_task = Task::all()->where('status', $this->status);
            } else if ($this->type == 'myticket') {
                $get_task = Task::all()->where('status', $this->status)->where('assigned_to', auth()->user()->id);
            } else if ($this->type == 'mywatched') {
                $get_task = Task::all()->where('status', $this->status)->whereIn('id', $data);
            }
        }

        $task = $get_task;
        $task_data = [];
        foreach ($task as $data) {
            $assigned_to = '';
            if (!empty($data->user->fullname)) {
                $assigned_to = $data->user->fullname;
            }

            $task_data [] = [
                str_pad($data->id, 5, '0', STR_PAD_LEFT),
                Carbon::parse($data->due_date)->format('M d, Y').' - '.Carbon::parse($data->time)->format('g:i A'),
                $data->title,
                $data->priority->name,
                $assigned_to,
                $data->creator->fullname,
                $data->created_at->format('M d, Y g:i A'),
                ucfirst($data->status),
            ];
        }
        $export = collect($task_data);
        return $export;
    }


    public function getWatchedIds($user_id)
    {
        $watch = Watcher::select('task_id')->where('user_id',$user_id)->get();
        return $watch;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:H1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);

            },
        ];
    }
}

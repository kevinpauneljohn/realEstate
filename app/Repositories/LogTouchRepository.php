<?php


namespace App\Repositories;


use App\LogTouch;
use PhpParser\Node\Expr\Array_;

class LogTouchRepository
{
    public function add_log_touches($data)
    {
        LogTouch::create($data);
        return array('success' => true, 'message' => 'Activity log successfully created!');
    }
}

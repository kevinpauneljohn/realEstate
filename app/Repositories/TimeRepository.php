<?php


namespace App\Repositories;


use DateTime;

class TimeRepository
{
    public function date_time($input_date, $input_time)
    {
        $date = $input_date;
        $time = new DateTime($input_time);

        $merge = new DateTime($date->format('Y-m-d') .' ' .$time->format('g:i A'));
        //echo $merge->format('Y-m-d H:i:s'); // Outputs '2017-03-14 13:37:42'

        $due = \Carbon\Carbon::parse($merge->format('Y-m-d g:i A')); // now date is a carbon instance
        return $due;
    }
}

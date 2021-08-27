<?php


namespace App\Services;


class ClientRequirementsService
{
    public static function clientRequirements($salesClientRequirements)
    {
        return $salesClientRequirements->count() > 0
            ? collect(collect($salesClientRequirements)->first()->requirements)->where('exists',true)->count().'/'.collect(collect($salesClientRequirements)->first()->requirements)->count()
            : 'N/A';
    }
}
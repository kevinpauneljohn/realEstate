<?php

namespace App\Filters;


use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class AdminRequestFiler implements FilterInterface
{
    public function transform($item)
    {
//        if (isset($item['permission']) ) {
//            $item['restricted'] = true;
//        }

        return $item;
    }
}

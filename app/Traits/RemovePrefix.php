<?php


namespace App\Traits;


Trait RemovePrefix
{
    public function editArrayKeys($search,$replace,$requests)
    {
        $array = array();
        foreach ($requests as $key => $value)
        {
            $key = str_replace($search,$replace,$key);
            $array[$key] = $value;
        }
        return $array;
    }
}

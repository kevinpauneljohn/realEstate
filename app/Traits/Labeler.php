<?php


namespace App\Traits;


trait Labeler
{
    public function roleColor($role)
    {
        switch ($role)
        {
            case 'client':
                return '<span class="badge badge-success">'.$role.'</span>';
                break;
            case 'architect':
                return '<span class="badge badge-warning">'.$role.'</span>';
                break;
            case 'builder admin':
                return '<span class="badge badge-info">'.$role.'</span>';
                break;
            case 'builder member':
                return '<span class="badge bg-purple">'.$role.'</span>';
                break;
            default:
                return '';
                break;
        }
    }
}

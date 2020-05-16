<?php


namespace App\Repositories;


class CannedMessageRepository
{
    public function filter($body)
    {
        $user = auth()->user();
        $search = array(
            '{full_name}',
            '{first_name}',
            '{middle_name}',
            '{last_name}',
            '{username}',
            '{mobile_no}',
            '{email}',
            '{address}'
        );
        $replace = array(
            ucfirst($user->fullname), //full name
            ucfirst($user->firstname), //first name
            ucfirst($user->middlename), //middle name
            ucfirst($user->lastname), //last name
            $user->username, // username
            $user->mobileNo, //mobile number
            $user->email, //email
            $user->address,//address
        );
        $filtered = str_replace($search,$replace,$body);
        return $filtered;
    }
}

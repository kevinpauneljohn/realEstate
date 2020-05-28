<?php


namespace App\Repositories;


use App\User;

class UserRepository
{
    /**
     *
     * */
    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function getUsersOriginalData($data)
    {
        $table = '<table class="table table-hover table-bordered">';
        $table .= '<tr><td>Upline</td><td>'.User::find($data['upline_id'])->fullname.'</td></tr>';
        $table .= '<tr><td>First Name</td><td>'.$data['firstname'].'</td></tr>';
        $table .= '<tr><td>Middle Name</td><td>'.$data['middlename'].'</td></tr>';
        $table .= '<tr><td>Last Name</td><td>'.$data['lastname'].'</td></tr>';
        $table .= '<tr><td>Mobile No.</td><td>'.$data['mobileNo'].'</td></tr>';
        $table .= '<tr><td>Address</td><td>'.$data['address'].'</td></tr>';
        $table .= '<tr><td>Date Of Birth</td><td>'.$data['date_of_birth'].'</td></tr>';
        $table .= '<tr><td>Email</td><td>'.$data['email'].'</td></tr>';
        $table .= '<tr><td>Username</td><td>'.$data['username'].'</td></tr>';
        $table .= '<tr><td>Password</td><td>********</td></tr>';
        $table .= '<tr><td>Role</td><td>'.$data['role'].'</td></tr>';
        $table .= '</table>';

        return $table;
    }

}

<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Events\CreateNetworkEvent;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->firstname = "john kevin";
        $user->middlename = "pama";
        $user->lastname = "paunel";
        $user->mobileNo = "09166520817";
        $user->date_of_birth = "09/09/1990";
        $user->email = "johnkevinpaunel@gmail.com";
        $user->username = "kevinpauneljohn";
        $user->password = bcrypt("123");
        $user->assignRole('super admin');
        $user->save();

        event(new CreateNetworkEvent($user->id));
    }
}

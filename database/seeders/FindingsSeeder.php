<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class FindingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view findings'])->syncRoles(['admin','Finance Admin']);
        Permission::create(['name' => 'add findings'])->syncRoles(['admin','Finance Admin']);
        Permission::create(['name' => 'edit findings'])->syncRoles(['admin','Finance Admin']);
        Permission::create(['name' => 'delete findings'])->syncRoles(['admin','Finance Admin']);
    }
}

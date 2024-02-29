<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Low',
                'description' => 'Low priority level',
                'days' => '5',
                'color' => '#00ff00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Normal',
                'description' => 'Normal priority level',
                'days' => '4',
                'color' => '#ff0080',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Warning',
                'description' => 'Warning priority level',
                'days' => '3',
                'color' => '#ff8000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Critical',
                'description' => 'Critical priority level',
                'days' => '1',
                'color' => '#ff0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Priority::insert($data);

        $data = [
            [
                'name' => 'update sales attribute',
                'description' => 'update sales attribute',
                'priority_id' => \App\Priority::where('name','critical')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'update sale status',
                'description' => 'update sale status',
                'priority_id' => \App\Priority::where('name','normal')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'create new user',
                'description' => 'create new user',
                'priority_id' => \App\Priority::where('name','critical')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'update user commission',
                'description' => 'update user commission',
                'priority_id' => \App\Priority::where('name','normal')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'delete user commission',
                'description' => 'delete user commission',
                'priority_id' => \App\Priority::where('name','normal')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'delete sales',
                'description' => 'delete sales',
                'priority_id' => \App\Priority::where('name','critical')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Action::insert($data);
    }
}

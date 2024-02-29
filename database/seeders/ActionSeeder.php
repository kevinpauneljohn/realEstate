<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
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

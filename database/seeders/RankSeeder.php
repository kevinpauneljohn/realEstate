<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
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
                'name'          => 'Member',
                'description'   => 'No sales yet',
                'start_points'  => 0.00,
                'end_points'    => 0.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Bronze 1',
                'description'   => 'Sales ranging from 100,000 to 15 million',
                'start_points'  => 1.00,
                'end_points'    => 150.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Bronze 2',
                'description'   => 'Sales from 15.1 m to 30 m',
                'start_points'  => 151.00,
                'end_points'    => 300.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Bronze 3',
                'description'   => 'Sales from 30.1 m to 60 m',
                'start_points'  => 301.00,
                'end_points'    => 600.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Silver 1',
                'description'   => 'Sales from 60.1m to 90m',
                'start_points'  => 601.00,
                'end_points'    => 900.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Silver 2',
                'description'   => 'Sales from 90.1 m to 120 m',
                'start_points'  => 901.00,
                'end_points'    => 1200.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Silver 3',
                'description'   => 'Sales from 120.1 m to 150 m',
                'start_points'  => 1201.00,
                'end_points'    => 1500.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Gold 1',
                'description'   => 'Sales from 150.1 m to 180 m',
                'start_points'  => 1501.00,
                'end_points'    => 1800.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Gold 2',
                'description'   => 'Sales from 180.1 m to 210 m',
                'start_points'  => 101.00,
                'end_points'    => 2100.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Gold 3',
                'description'   => 'Sales from 210.1 m to 250 m',
                'start_points'  => 2101.00,
                'end_points'    => 2500.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Platinum 1',
                'description'   => 'Sales from 250.1 m to 300 m',
                'start_points'  => 2501.00,
                'end_points'    => 3000.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Platinum 2',
                'description'   => 'Sales from 300.1 m to 350 m',
                'start_points'  => 3001.00,
                'end_points'    => 3500.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Platinum 3',
                'description'   => 'Sales from 350.1 m to 400 m',
                'start_points'  => 3501.00,
                'end_points'    => 4000.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Titanium 1',
                'description'   => 'Sales from 400.1 m to 500 m',
                'start_points'  => 4001.00,
                'end_points'    => 5000.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Titanium 2',
                'description'   => 'Sales from 500.1 m to 600 m',
                'start_points'  => 5001.00,
                'end_points'    => 6000.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Titanium 3',
                'description'   => 'Sales from 600.1 m to 700 m',
                'start_points'  => 6001.00,
                'end_points'    => 7000.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Diamond',
                'description'   => 'Sales from 700.1 m to 850 m',
                'start_points'  => 7001.00,
                'end_points'    => 8500.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Double Diamond',
                'description'   => 'Sales from 850.1 m to 1B',
                'start_points'  => 8501.00,
                'end_points'    => 10000.00,
                'timeline'      => 'lifetime',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Legendary',
                'description'   => 'Agents who achieved 500 m within 1 year only',
                'start_points'  => 5000.00,
                'end_points'    => 5000.00,
                'timeline'      => '1 year',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Mythical',
                'description'   => 'Agents who achieved 500 m sales within 6 months only',
                'start_points'  => 5000.00,
                'end_points'    => 5000.00,
                'timeline'      => '6 year',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        \App\Rank::insert($data);
    }
}

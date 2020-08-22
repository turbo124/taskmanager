<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('task_statuses')->insert([
            'title' => 'TODO',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-bars',
            'is_active' => 1,
            'task_type' => 1,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Blocked',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-lightbulb',
            'is_active' => 1,
            'task_type' => 1,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'In Progress',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-spinner',
            'is_active' => 1,
            'task_type' => 1,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Done',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 1,
                ]
        );

        // deals
        DB::table('task_statuses')->insert([
            'title' => 'Prospecting',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );
 
        DB::table('task_statuses')->insert([
            'title' => 'Qualification',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

       DB::table('task_statuses')->insert([
            'title' => 'Needs Analysis',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Value Propsition',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Identified Decision Makers',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Perception Analysis',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Proposal Price\Quote',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Closed Won',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );

        DB::table('task_statuses')->insert([
            'title' => 'Closed Lost',
            'description' => 'You can do what you want to do with this column',
            'icon' => 'fa-check',
            'is_active' => 1,
            'task_type' => 2,
                ]
        );
    }

}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('professions')->insert([
            'title' => 'Desarrollador back-end'
        ]);

        DB::table('professions')->insert([
            'title' => 'Desarrollador front-end'
        ]);

        DB::table('professions')->insert([
            'title' => 'Diseñador web'
        ]);
    }
}

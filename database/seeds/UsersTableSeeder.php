<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $professions = DB::select('SELECT id FROM professions WHERE title = :title LIMIT 0,1', ['title' => 'Desarrollador back-end']);

        $professionId = DB::table('professions')
            ->whereTitle('Desarrollador back-end')
            ->value('id');

        DB::table('users')->insert([
            'profession_id' => $professionId,
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => bcrypt('secret')
        ]);
    }
}

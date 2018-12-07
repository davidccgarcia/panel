<?php

use App\User;
use App\Profession;
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

        $professionId = Profession::whereTitle('Desarrollador back-end')->value('id');

        // DB::table('users')->insert([
        //     'profession_id' => $professionId,
        //     'name' => 'David Garcia',
        //     'email' => 'ccristhiangarcia@gmail.com',
        //     'password' => bcrypt('secret')
        // ]);

        // $data = [
        //    'profession_id' => $professionId,
        //    'name' => 'David Garcia',
        //    'email' => 'ccristhiangarcia@gmail.com',
        //    'password' => bcrypt('secret')
        // ];

        User::create([
            'profession_id' => $professionId,
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => bcrypt('secret')
        ]);

        // DB::insert('INSERT INTO users (profession_id, name, email, password) VALUES (:profession_id, :name, :email, :password)', $data);
    }
}

<?php

use Illuminate\Database\Seeder;

use App\Skill;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Skill::class)->create(['name' => 'HTML']);
        factory(Skill::class)->create(['name' => 'PHP']);
        factory(Skill::class)->create(['name' => 'Vue']);
        factory(Skill::class)->create(['name' => 'TDD']);
        factory(Skill::class)->create(['name' => 'OOP']);
        factory(Skill::class)->create(['name' => 'CSS']);
        factory(Skill::class)->create(['name' => 'JS']);
    }
}

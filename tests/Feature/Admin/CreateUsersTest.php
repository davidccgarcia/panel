<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\{Profession, Skill, User};
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'David Garcia',
        'email' => 'ccristhiangarcia@gmail.com',
        'password' => '123456',
        'bio' => 'Desarrollador de Laravel y Vue.js',
        'profession_id' => '',
        'twitter' => 'https://twitter.com/davidccgarcia',
        'role' => 'user',
    ];

    /**
     * @test
     */
    public function it_loads_the_new_users_page()
    {
        $profession = factory(Profession::class)->create();
        $HTML = factory(Skill::class)->create([
            'name' => 'HTML'
        ]);

        $PHP = factory(Skill::class)->create([
            'name' => 'PHP'
        ]);

        $this->get('users/create')
            ->assertStatus(200)
            ->assertSee('Crear usuario')
            ->assertViewHas('skills', function ($skills) use ($PHP, $HTML) {
                return $skills->contains($PHP) && $skills->contains($HTML);
            })
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            });
    }

    /**
     * @test
     */
    public function it_create_a_new_user()
    {
        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->post('users', $this->withData([
            'skills' => [$skillA->id, $skillB->id],
            'profession_id' => $profession->id,
        ]));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456',
            'role' => 'user'
        ]);

        $user = User::findByEmail('ccristhiangarcia@gmail.com');

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/davidccgarcia',
            'user_id' => $user->id,
            'profession_id' => $profession->id,
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id,
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id,
        ]);

        $this->assertDatabaseMissing('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillC->id,
        ]);
    }

    /**
     * @test
     */
    public function the_twitter_field_is_optional()
    {
        $this->post('users', $this->withData([
            'twitter' => null,
        ]));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'twitter' => null,
            'user_id' => User::findByEmail('ccristhiangarcia@gmail.com')->id,
        ]);
    }

    /**
     * @test
     */
    public function the_role_field_is_optional()
    {
        $this->post('users', $this->withData([
            'role' => null,
        ]));

        $this->assertDatabaseHas('users', [
            'email' => 'ccristhiangarcia@gmail.com',
            'role' => 'user',
        ]);
    }

    /**
     * @test
     */
    public function the_role_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'role' => 'invalid-role',
        ]))->assertSessionHasErrors('role');

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_profession_id_field_is_optional()
    {
        $this->post('users', $this->withData([
            'profession_id' => null,
        ]));

        $this->assertCredentials([
            'name' => 'David Garcia',
            'email' => 'ccristhiangarcia@gmail.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Desarrollador de Laravel y Vue.js',
            'user_id' => User::findByEmail('ccristhiangarcia@gmail.com')->id,
            'profession_id' => null,
        ]);
    }

    /**
     * @test
     */
    public function the_name_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'name' => '',
        ]))->assertSessionHasErrors(['name']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_user_is_redirected_to_the_previous_page_when_the_validation_fails()
    {
        $this->handleValidationExceptions();

        $this->from(route('users'))
            ->post('users', [])
            ->assertRedirect('users');

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_profession_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'profession_id' => '999',
        ]))->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'skills' => 'HTML, CSS, JS',
        ]))->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->post('users', $this->withData([
            'skills' => [$skillA->id, $skillB->id + 1],
        ]))->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function only_no_deleted_at_professions_are_valid()
    {
        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'profession_id' => $deletedProfession->id,
        ]))->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_email_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'email' => '',
        ]))->assertSessionHasErrors(['email']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_email_is_valid()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'email' => 'correo-no-valido'
        ]))->assertSessionHasErrors(['email']);

        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function the_email_is_unique()
    {
        $this->handleValidationExceptions();

        factory(User::class)->create([
            'email' => 'ccristhiangarcia@gmail.com'
        ]);

        $this->post('users', $this->withData([
            'email' => 'ccristhiangarcia@gmail.com',
        ]))->assertSessionHasErrors(['email']);

        // $this->assertEquals(1, User::count());
        $this->assertDatabaseCount('users');
    }

    /**
     * @test
     */
    public function the_password_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('users', $this->withData([
            'password' => ''
        ]))->assertSessionHasErrors(['password']);

        $this->assertDatabaseEmpty('users');
    }
}

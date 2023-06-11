<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * @return void
     */
    public function testRegularUserCantSeeListOfUsers(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testRegularUserCantSeeSingleUser(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/users/'.$user->id);
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testRegularUserCantOpenEditUserForm(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/users/'.$user->id.'/edit');
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testRegularUserCantEditUser(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/users/'.$user->id, $user->toArray());
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testRegularUserCantDeleteUser(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/users/'.$user->id);
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testCanReadListOfUsers(): void
    {
        $userOne = User::factory()->admin()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $userOne->assignRole($adminRole);
        $userTwo = User::factory()->create();
        $response = $this->actingAs($userOne)->get('/users');
        $response->assertSee($userOne->name)
        ->assertSee($userOne->email)
        ->assertSee($userTwo->name)
        ->assertSee($userTwo->email);
    }

    /**
     * @return void
     */
    public function testCanReadSingleUsers(): void
    {
        $userOne = User::factory()->admin()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $userOne->assignRole($adminRole);
        $userTwo = User::factory()->create();
        $response = $this->actingAs($userOne)->get('/users/'.$userTwo->id);
        $response->assertSee($userTwo->name)->assertSee($userTwo->email);
    }

    /**
     * @return void
     */
    public function testCanOpenUserEdition(): void
    {
        $user = User::factory()->admin()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $user->assignRole($adminRole);
        $response = $this->actingAs($user)->get('/users/'.$user->id.'/edit');
        $response->assertSee($user->name)->assertSee($user->email);
    }

    /**
     * @return void
     */
    public function testCanEditUser(): void
    {
        $user = User::factory()->admin()->create();
        $user->name = 'Updated name';
        $user->email = 'updated@email.com';
        $adminRole = Role::create(['name' => 'admin']);
        $user->assignRole($adminRole);
        $this->actingAs($user)->put('/users/'.$user->id, $user->toArray());
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated name', 'email' => 'updated@email.com']);
    }

    /**
     * @return void
     */
    public function testCanDeleteUser(): void
    {
        $user = User::factory()->admin()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $user->assignRole($adminRole);
        $this->actingAs($user);
        $this->delete('/users/'.$user->id);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}

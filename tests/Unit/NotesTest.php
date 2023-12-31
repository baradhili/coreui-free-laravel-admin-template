<?php

namespace Tests\Unit;

use App\Models\Notes;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use DatabaseMigrations;

    public function testCanReadListOfNotes(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $noteOne = Notes::factory()->create();
        $noteTwo = Notes::factory()->create();
        $response = $this->actingAs($user)->get('/notes');
        $response->assertSee($noteOne->title)
        ->assertSee($noteOne->content)
        ->assertSee($noteTwo->title)
        ->assertSee($noteTwo->content);
    }

    public function testCanReadSingleNote(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $note = Notes::factory()->create();
        $response = $this->actingAs($user)->get('/notes/'.$note->id);
        $response->assertSee($note->title)->assertSee($note->content);
    }

    public function testCanOpenNoteCreateForm(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $note = Notes::factory()->create();
        $response = $this->actingAs($user)->get('/notes/create');
        $response->assertSee('Create Note');
    }

    public function testCanCreateNewNote(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $note = Notes::factory()->create();
        $response = $this->actingAs($user)->post('/notes', $note->toArray());
        $this->assertDatabaseHas('notes', ['title' => $note->title, 'content' => $note->content]);
    }

    public function testCanOpenNoteEdition(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $note = Notes::factory()->create();
        $response = $this->actingAs($user)->get('/notes/'.$note->id.'/edit');
        $response->assertSee($note->title)->assertSee($note->content);
    }

    public function testCanEditNote(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $note = Notes::factory()->create();
        $note->title = 'Updated title';
        $note->content = 'Updated content';
        $this->actingAs($user)->put('/notes/'.$user->id, $note->toArray());
        $this->assertDatabaseHas('notes', ['id' => $note->id, 'title' => 'Updated title', 'content' => 'Updated content']);
    }

    public function testCanDeleteNote(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $note = Notes::factory()->create();
        $this->actingAs($user);
        $this->delete('/notes/'.$note->id);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}

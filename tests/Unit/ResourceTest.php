<?php

namespace Tests\Unit;

use App\Models\Example;
//use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use DatabaseMigrations;
    // use WithFaker;

    public function helperCreateExample($name)
    {
        $example = new Example();
        $example->name = $name;
        $example->description = 'Lorem ipsum dolor';
        $example->status_id = 1;
        $example->save();

        return $example->id;
    }

     public function helperCreateForm($name = 'test', $permissions = true)
     {
         $form = new Form();
         $form->name = $name;
         $form->table_name = 'example';
         if ($permissions) {
             $form->read = 1;
             $form->edit = 1;
             $form->add = 1;
             $form->delete = 1;
         } else {
             $form->read = 0;
             $form->edit = 0;
             $form->add = 0;
             $form->delete = 0;
         }
         $form->pagination = 5;
         $form->save();
         $this->helperCreateStatusRecord();
         $this->helperCreateStatusRecord();
         $this->helperCreateFormField($form->id, 'name');
         $this->helperCreateFormField($form->id, 'description');
         $this->helperCreateFormField($form->id, 'status_id', true);

         return $form->id;
     }

     public function helperCreateFormField($formId, $columnName = 'test', $relation = false)
     {
         $field = new FormField();
         $field->name = $columnName;
         $field->column_name = $columnName;
         $field->type = 'text';
         $field->browse = 1;
         $field->read = 1;
         $field->edit = 1;
         $field->add = 1;
         if ($relation) {
             $field->relation_table = 'status';
             $field->relation_column = 'name';
         }
         $field->form_id = $formId;
         $field->save();
     }

     public function helperCreateStatusRecord()
     {
         $status = new Status();
         $status->name = 'Lorem ipsum dolor';
         $status->class = 'primary';
         $status->save();
     }

     public function helperCreateRoleGuest($formId = 1, $permissions = false)
     {
         $role = Role::create(['name' => 'guest']);
         if ($permissions) {
             $this->helperCreatePermissions($role, $formId);
         }

         return $role;
     }

     public function helperCreatePermissions($role, $formId = 1)
     {
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
     }

     public function testIndexAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource');
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testIndex(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $response = $this->get('/resource/'.$formId.'/resource');
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Record_name_1');
         $response->assertSee('Form_name_1');
     }

     public function testIndexActingAsUserNoGuest(): void
     {
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $response = $this->actingAs($user)->get('/resource/'.$formId.'/resource');
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Record_name_1');
         $response->assertSee('Form_name_1');
     }

     public function testCreateAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource/create');
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testCreateAccessDEnitedTwo(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource/create');
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testCreate(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $response = $this->get('/resource/'.$formId.'/resource/create');
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
         $response->assertSee('name');
         $response->assertSee('description');
         $response->assertSee('status_id');
     }

     public function testCreateActingAsUserNoGuest(): void
     {
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $response = $this->actingAs($user)->get('/resource/'.$formId.'/resource/create');
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
         $response->assertSee('name');
         $response->assertSee('description');
         $response->assertSee('status_id');
     }

     public function testStoreAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->post('/resource/'.$formId.'/resource');
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testStoreAccessDeniedTwo(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $postData = [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ];
         $response = $this->post('/resource/'.$formId.'/resource', $postData);
         $this->assertDatabaseMissing('example', [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ]);
         $response->assertStatus(401);
     }

     public function testStore(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $postData = [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ];
         $response = $this->post('/resource/'.$formId.'/resource', $postData);
         $this->assertDatabaseHas('example', [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ]);
         $response->assertStatus(302);
     }

     public function testStoreActingAsUserNoGuest(): void
     {
         $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $postData = [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ];
         $response = $this->actingAs($user)->post('/resource/'.$formId.'/resource', $postData);
         $this->assertDatabaseHas('example', [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ]);
         $response->assertStatus(302);
     }

     public function testShowAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testShowAccessDeniedTwo(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testShow(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $response = $this->get('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
         $response->assertSee('name');
         $response->assertSee('description');
         $response->assertSee('status_id');
     }

     public function testShowActingAsUserNoGuest(): void
     {
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $response = $this->actingAs($user)->get('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
         $response->assertSee('name');
         $response->assertSee('description');
         $response->assertSee('status_id');
     }

     public function testEditAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource/'.$exampleId.'/edit');
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testEditAccessDeniedTwo(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->get('/resource/'.$formId.'/resource/'.$exampleId.'/edit');
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testEdit(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $response = $this->get('/resource/'.$formId.'/resource/'.$exampleId.'/edit');
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
         $response->assertSee('name');
         $response->assertSee('description');
         $response->assertSee('status_id');
         $response->assertSee('Record_name_1');
     }

     public function testEditActingAsUserNoGuest(): void
     {
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $response = $this->actingAs($user)->get('/resource/'.$formId.'/resource/'.$exampleId.'/edit');
         //var_dump( $response->getContent() );
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
         $response->assertSee('name');
         $response->assertSee('description');
         $response->assertSee('status_id');
         $response->assertSee('Record_name_1');
     }

     public function testUpdateAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->put('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testUpdateAccessDeniedTwo(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $postData = [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ];
         $response = $this->put('/resource/'.$formId.'/resource/'.$exampleId, $postData);
         //var_dump( $response->getContent() );
         $response->assertStatus(401);
     }

     public function testUpdate(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $postData = [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ];
         $response = $this->put('/resource/'.$formId.'/resource/'.$exampleId, $postData);
         //var_dump( $response->getContent() );
         $this->assertDatabaseHas('example', [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ]);
         $response->assertStatus(302);
     }

     public function testUpdateActingAsUserNoGuest(): void
     {
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $postData = [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ];
         $response = $this->actingAs($user)->put('/resource/'.$formId.'/resource/'.$exampleId, $postData);
         //var_dump( $response->getContent() );
         $this->assertDatabaseHas('example', [
             'name' => 'aaa',
             'description' => 'bbb',
             'status_id' => '7',
         ]);
         $response->assertStatus(302);
     }

     public function testDeleteAccessDenied(): void
     {
         $this->helperCreateRoleGuest(1, false);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->delete('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $this->assertDatabaseHas('example', [
             'name' => 'Record_name_1',
         ]);
         $response->assertStatus(401);
     }

     public function testDeleteAccessDeniedTwo(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', false);
         $response = $this->delete('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $this->assertDatabaseHas('example', [
             'name' => 'Record_name_1',
         ]);
         $response->assertStatus(401);
     }

     public function testDelete(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $response = $this->delete('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $this->assertDatabaseHas('example', [
             'name' => 'Record_name_1',
         ]);
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
     }

     public function testDeleteMarker(): void
     {
         $this->helperCreateRoleGuest(1, true);
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $postData = ['marker' => true];
         $response = $this->delete('/resource/'.$formId.'/resource/'.$exampleId, $postData);
         //var_dump( $response->getContent() );
         $response->assertStatus(302);
         $this->assertDatabaseMissing('example', ['name' => 'Record_name_1']);
     }

     public function testDeleteActingAsUserNoGuest(): void
     {
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $response = $this->actingAs($user)->delete('/resource/'.$formId.'/resource/'.$exampleId);
         //var_dump( $response->getContent() );
         $this->assertDatabaseHas('example', [
             'name' => 'Record_name_1',
         ]);
         $response->assertStatus(200);
         $response->assertSee('Form_name_1');
     }

     public function testDeleteMarkerActingAsUserNoGuest(): void
     {
         $exampleId = $this->helperCreateExample('Record_name_1');
         $formId = $this->helperCreateForm('Form_name_1', true);
         $user = User::factory()->create();
         $role = Role::create(['name' => 'user']);
         $user->assignRole($role);
         Permission::create(['name' => 'browse bread '.$formId]);
         Permission::create(['name' => 'read bread '.$formId]);
         Permission::create(['name' => 'edit bread '.$formId]);
         Permission::create(['name' => 'add bread '.$formId]);
         Permission::create(['name' => 'delete bread '.$formId]);
         $role->givePermissionTo('browse bread '.$formId);
         $role->givePermissionTo('read bread '.$formId);
         $role->givePermissionTo('edit bread '.$formId);
         $role->givePermissionTo('add bread '.$formId);
         $role->givePermissionTo('delete bread '.$formId);
         $postData = ['marker' => true];
         $response = $this->actingAs($user)->delete('/resource/'.$formId.'/resource/'.$exampleId, $postData);
         //var_dump( $response->getContent() );
         $response->assertStatus(302);
         $this->assertDatabaseMissing('example', ['name' => 'Record_name_1']);
     }
}

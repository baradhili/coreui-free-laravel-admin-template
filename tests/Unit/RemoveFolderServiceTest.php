<?php

namespace Tests\Unit;

use App\Models\Folder;
use App\Services\RemoveFolderService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RemoveFolderServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testFindFolderChildrens(): void
    {
        $folder = new Folder();
        $folder->name = 'test1';
        $folder->save();
        $folder2 = new Folder();
        $folder2->name = 'test2';
        $folder2->folder_id = $folder->id;
        $folder2->save();
        $media = $folder2->getMedia()->first();
        $removeFolderService = new RemoveFolderService();
        $this->assertSame($removeFolderService->foldersArray, []);
        $removeFolderService->findFolderChildAnd($folder->id, null);
        $result = $removeFolderService->foldersArray;
        $this->assertSame($result[0], $folder->id);
        $this->assertSame($result[1], $folder2->id);
    }

    public function testFolderDelete(): void
    {
        $folder = new Folder();
        $folder->name = 'test1';
        $folder->save();
        $folder2 = new Folder();
        $folder2->name = 'test2';
        $folder2->folder_id = $folder->id;
        $folder2->save();
        $folder3 = new Folder();
        $folder3->name = 'test3';
        $folder3->folder_id = $folder2->id;
        $folder3->save();
        $folder4 = new Folder();
        $folder4->name = 'test4';
        $folder4->folder_id = $folder->id;
        $folder4->save();
        $removeFolderService = new RemoveFolderService();
        $this->assertDatabaseHas('folder', ['name' => 'test1']);
        $this->assertDatabaseHas('folder', ['name' => 'test2']);
        $this->assertDatabaseHas('folder', ['name' => 'test3']);
        $this->assertDatabaseHas('folder', ['name' => 'test4']);
        $removeFolderService->folderDelete($folder2->id, null);
        $this->assertDatabaseHas('folder', ['name' => 'test1']);
        $this->assertDatabaseMissing('folder', ['name' => 'test2']);
        $this->assertDatabaseMissing('folder', ['name' => 'test3']);
        $this->assertDatabaseHas('folder', ['name' => 'test4']);
    }
}

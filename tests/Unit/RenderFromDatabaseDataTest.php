<?php

namespace Tests\Unit;

use App\MenuBuilder\RenderFromDatabaseData;
use Tests\TestCase;

class RenderFromDatabaseDataTest extends TestCase
{
    /**
     * @return void
     */
    public function testRenderSimpleInput(): void
    {
        $input = [
            [
                'id' => '1',
                'name' => 'Dashboard',
                'href' => '/',
                'icon' => 'cui-speedometer',
                'slug' => 'link',
                'parent_id' => null,
                'menu_id' => '1',
                'sequence' => '1',
            ],
        ];
        $provided = [[
            'id' => '1',
            'slug' => 'link',
            'name' => 'Dashboard',
            'href' => '/',
            'hasIcon' => true,
            'icon' => 'cui-speedometer',
            'iconType' => 'coreui',
            'sequence' => '1',
        ]];
        $rfd = new RenderFromDatabaseData();
        $result = $rfd->render($input);
        $this->assertSame($result, $provided);
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $input = [
            [
                'id' => '1',
                'name' => 'Dashboard',
                'href' => '/',
                'icon' => 'cui-speedometer',
                'slug' => 'link',
                'parent_id' => null,
                'menu_id' => '1',
                'sequence' => '1',
            ],
            [
                'id' => '7',
                'name' => 'Base',
                'href' => null,
                'icon' => 'cui-puzzle',
                'slug' => 'dropdown',
                'parent_id' => null,
                'menu_id' => '1',
                'sequence' => '7',
            ],
            [
                'id' => '8',
                'name' => 'Breadcrumb',
                'href' => '/base/breadcrumb',
                'icon' => null,
                'slug' => 'link',
                'parent_id' => '7',
                'menu_id' => '1',
                'sequence' => '8',
            ],
        ];
        $provided = [[
            'id' => '1',
            'slug' => 'link',
            'name' => 'Dashboard',
            'href' => '/',
            'hasIcon' => true,
            'icon' => 'cui-speedometer',
            'iconType' => 'coreui',
            'sequence' => '1',
        ],
            [
                'id' => '7',
                'slug' => 'dropdown',
                'name' => 'Base',
                'hasIcon' => true,
                'icon' => 'cui-puzzle',
                'iconType' => 'coreui',
                'elements' => [
                    [
                        'id' => '8',
                        'slug' => 'link',
                        'name' => 'Breadcrumb',
                        'href' => '/base/breadcrumb',
                        'hasIcon' => false,
                        'sequence' => '8',
                    ],
                ],
                'sequence' => '7',
            ]];
        $rfd = new RenderFromDatabaseData();
        $result = $rfd->render($input);
        $this->assertSame($result, $provided);
    }
}

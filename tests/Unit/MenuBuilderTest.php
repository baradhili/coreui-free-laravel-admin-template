<?php

namespace Tests\Unit;

use App\MenuBuilder\MenuBuilder;
use Tests\TestCase;

class MenuBuilderTest extends TestCase
{
    public function testAddSingleLinkWitchIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'link',
            'name' => 'name',
            'href' => '/href',
            'hasIcon' => true,
            'icon' => 'icon',
            'iconType' => 'not_core_ui',
            'sequence' => 4,
        ]];
        $mb = new MenuBuilder();
        $mb->addLink(1, 'name', '/href', 'icon', 'not_core_ui', 4);
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddSingleLinkWitchDefaultTypeOfIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'link',
            'name' => 'name',
            'href' => '/href',
            'hasIcon' => true,
            'icon' => 'icon',
            'iconType' => 'coreui',
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->addLink(1, 'name', '/href', 'icon');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddSingleLinkNoIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'link',
            'name' => 'name',
            'href' => '/href',
            'hasIcon' => false,
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->addLink(1, 'name', '/href');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddThreeLinks(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'link',
            'name' => 'nameOne',
            'href' => '/hrefOne',
            'hasIcon' => false,
            'sequence' => 0,
        ], [
            'id' => 2,
            'slug' => 'link',
            'name' => 'nameTwo',
            'href' => '/hrefTwo',
            'hasIcon' => false,
            'sequence' => 0,
        ], [
            'id' => 3,
            'slug' => 'link',
            'name' => 'nameThree',
            'href' => '/hrefThree',
            'hasIcon' => false,
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->addLink(1, 'nameOne', '/hrefOne');
        $mb->addLink(2, 'nameTwo', '/hrefTwo');
        $mb->addLink(3, 'nameThree', '/hrefThree');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddSingleTitleWitchIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'title',
            'name' => 'name',
            'hasIcon' => true,
            'icon' => 'icon',
            'iconType' => 'not_core_ui',
            'sequence' => 4,
        ]];
        $mb = new MenuBuilder();
        $mb->addTitle(1, 'name', 'icon', 'not_core_ui', 4);
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddSingleTitleWitchDefaultIconType(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'title',
            'name' => 'name',
            'hasIcon' => true,
            'icon' => 'icon',
            'iconType' => 'coreui',
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->addTitle(1, 'name', 'icon');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddSingleTitleNoIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'title',
            'name' => 'name',
            'hasIcon' => false,
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->addTitle(1, 'name');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testAddThreeTitle(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'title',
            'name' => 'nameOne',
            'hasIcon' => false,
            'sequence' => 0,
        ], [
            'id' => 2,
            'slug' => 'title',
            'name' => 'nameTwo',
            'hasIcon' => false,
            'sequence' => 0,
        ], [
            'id' => 3,
            'slug' => 'title',
            'name' => 'nameThree',
            'hasIcon' => false,
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->addTitle(1, 'nameOne');
        $mb->addTitle(2, 'nameTwo');
        $mb->addTitle(3, 'nameThree');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testBeginDropdownWitchIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'dropdown',
            'name' => 'name',
            'hasIcon' => true,
            'icon' => 'icon',
            'iconType' => 'not_core_ui',
            'elements' => [],
            'sequence' => 4,
        ]];
        $mb = new MenuBuilder();
        $mb->beginDropdown(1, 'name', 'icon', 'not_core_ui', 4);
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testBeginDropdownWitchDefaultIconType(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'dropdown',
            'name' => 'name',
            'hasIcon' => true,
            'icon' => 'icon',
            'iconType' => 'coreui',
            'elements' => [],
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->beginDropdown(1, 'name', 'icon');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testBeginDropdownWitchNoIcon(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'dropdown',
            'name' => 'name',
            'hasIcon' => false,
            'elements' => [],
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->beginDropdown(1, 'name');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testThreeBeginDropdown(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'dropdown',
            'name' => 'nameOne',
            'hasIcon' => false,
            'elements' => [],
            'sequence' => 0,
        ], [
            'id' => 2,
            'slug' => 'dropdown',
            'name' => 'nameTwo',
            'hasIcon' => false,
            'elements' => [],
            'sequence' => 0,
        ], [
            'id' => 3,
            'slug' => 'dropdown',
            'name' => 'nameThree',
            'hasIcon' => false,
            'elements' => [],
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->beginDropdown(1, 'nameOne');
        $mb->endDropdown();
        $mb->beginDropdown(2, 'nameTwo');
        $mb->endDropdown();
        $mb->beginDropdown(3, 'nameThree');
        $mb->endDropdown();
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testBeginDropdownWitchTwoElements(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'dropdown',
            'name' => 'name',
            'hasIcon' => false,
            'elements' => [
                [
                    'id' => 2,
                    'slug' => 'link',
                    'name' => 'nameOne',
                    'href' => '/href',
                    'hasIcon' => false,
                    'sequence' => 0,
                ],
                [
                    'id' => 3,
                    'slug' => 'link',
                    'name' => 'nameTwo',
                    'href' => '/href',
                    'hasIcon' => false,
                    'sequence' => 0,
                ],
            ],
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->beginDropdown(1, 'name');
        $mb->addLink(2, 'nameOne', '/href');
        $mb->addLink(3, 'nameTwo', '/href');
        $mb->endDropdown();
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testLinkInDropdownInDropdown(): void
    {
        $provided = [[
            'id' => 1,
            'slug' => 'dropdown',
            'name' => 'name',
            'hasIcon' => false,
            'elements' => [[
                'id' => 2,
                'slug' => 'dropdown',
                'name' => 'nameOne',
                'hasIcon' => false,
                'elements' => [[
                    'id' => 3,
                    'slug' => 'link',
                    'name' => 'nameTwo',
                    'href' => '/href',
                    'hasIcon' => false,
                    'sequence' => 0,
                ]],
                'sequence' => 0,
            ]],
            'sequence' => 0,
        ]];
        $mb = new MenuBuilder();
        $mb->beginDropdown(1, 'name');
        $mb->beginDropdown(2, 'nameOne');
        $mb->addLink(3, 'nameTwo', '/href');
        $mb->endDropdown();
        $mb->endDropdown();
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }

    public function testComplex(): void
    {
        $provided = [
            [
                'id' => 1,
                'slug' => 'title',
                'name' => 'name',
                'hasIcon' => false,
                'sequence' => 0,
            ],
            [
                'id' => 2,
                'slug' => 'dropdown',
                'name' => 'nameOne',
                'hasIcon' => false,
                'elements' => [
                    [
                        'id' => 3,
                        'slug' => 'link',
                        'name' => 'nameTwo',
                        'href' => '/href',
                        'hasIcon' => false,
                        'sequence' => 0,
                    ],
                    [
                        'id' => 4,
                        'slug' => 'link',
                        'name' => 'nameTwo',
                        'href' => '/href',
                        'hasIcon' => false,
                        'sequence' => 0,
                    ],
                    [
                        'id' => 5,
                        'slug' => 'dropdown',
                        'name' => 'nameOne',
                        'hasIcon' => false,
                        'elements' => [[
                            'id' => 6,
                            'slug' => 'link',
                            'name' => 'nameTwo',
                            'href' => '/href',
                            'hasIcon' => false,
                            'sequence' => 0,
                        ]],
                        'sequence' => 0,
                    ],
                    [
                        'id' => 7,
                        'slug' => 'link',
                        'name' => 'nameTwo',
                        'href' => '/href',
                        'hasIcon' => false,
                        'sequence' => 0,
                    ],
                ],
                'sequence' => 0,
            ],
            [
                'id' => 8,
                'slug' => 'link',
                'name' => 'nameTwo',
                'href' => '/href',
                'hasIcon' => false,
                'sequence' => 0,
            ],
        ];
        $mb = new MenuBuilder();
        $mb->addTitle(1, 'name');
        $mb->beginDropdown(2, 'nameOne');
        $mb->addLink(3, 'nameTwo', '/href');
        $mb->addLink(4, 'nameTwo', '/href');
        $mb->beginDropdown(5, 'nameOne');
        $mb->addLink(6, 'nameTwo', '/href');

        $mb->endDropdown();
        $mb->addLink(7, 'nameTwo', '/href');

        $mb->endDropdown();
        $mb->addLink(8, 'nameTwo', '/href');
        $result = $mb->getResult();
        $this->assertSame($provided, $result);
    }
}

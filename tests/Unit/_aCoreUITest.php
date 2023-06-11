<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class _aCoreUITest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testHomepage(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testColors(): void
    {
        $response = $this->get('/colors');
        $response->assertStatus(403);
    }

    public function testColorsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/colors');
        $response->assertStatus(200);
    }

    public function testTypography(): void
    {
        $response = $this->get('/typography');
        $response->assertStatus(403);
    }

    public function testTypographyActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/typography');
        $response->assertStatus(200);
    }

/* ################   BASE   ############### */
    public function testBaseBreadcrumb(): void
    {
        $response = $this->get('/base/breadcrumb');
        $response->assertStatus(403);
    }

    public function testBaseBreadcrumbActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/breadcrumb');
        $response->assertStatus(200);
    }

    public function testBaseCards(): void
    {
        $response = $this->get('/base/cards');
        $response->assertStatus(403);
    }

    public function testBaseCardsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/cards');
        $response->assertStatus(200);
    }

    public function testBaseCarousel(): void
    {
        $response = $this->get('/base/carousel');
        $response->assertStatus(403);
    }

    public function testBaseCarouselActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/carousel');
        $response->assertStatus(200);
    }

    public function testBaseCollapse(): void
    {
        $response = $this->get('/base/collapse');
        $response->assertStatus(403);
    }

    public function testBaseCollapseActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/collapse');
        $response->assertStatus(200);
    }

    public function testBaseForms(): void
    {
        $response = $this->get('/base/forms');
        $response->assertStatus(403);
    }

    public function testBaseFormsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/forms');
        $response->assertStatus(200);
    }

    public function testBaseJumbotron(): void
    {
        $response = $this->get('/base/jumbotron');
        $response->assertStatus(403);
    }

    public function testBaseJumbotronActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/jumbotron');
        $response->assertStatus(200);
    }

    public function testBaseListgroup(): void
    {
        $response = $this->get('/base/list-group');
        $response->assertStatus(403);
    }

    public function testBaseBaseListgroupActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/list-group');
        $response->assertStatus(200);
    }

    public function testBaseNavs(): void
    {
        $response = $this->get('/base/navs');
        $response->assertStatus(403);
    }

    public function testBaseNavsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/navs');
        $response->assertStatus(200);
    }

    public function testBasePagination(): void
    {
        $response = $this->get('/base/pagination');
        $response->assertStatus(403);
    }

    public function testBasePaginationActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/pagination');
        $response->assertStatus(200);
    }

    public function testBasePopovers(): void
    {
        $response = $this->get('/base/popovers');
        $response->assertStatus(403);
    }

    public function testBasePopoversActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/popovers');
        $response->assertStatus(200);
    }

    public function testBaseProgress(): void
    {
        $response = $this->get('/base/progress');
        $response->assertStatus(403);
    }

    public function testBaseProgressActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/progress');
        $response->assertStatus(200);
    }

    public function testBaseScrollSpy(): void
    {
        $response = $this->get('/base/scrollspy');
        $response->assertStatus(403);
    }

    public function testBaseScrollspyActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/scrollspy');
        $response->assertStatus(200);
    }

    public function testBaseSwitches(): void
    {
        $response = $this->get('/base/switches');
        $response->assertStatus(403);
    }

    public function testBaseSwitchesActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/switches');
        $response->assertStatus(200);
    }

    public function testBaseTables(): void
    {
        $response = $this->get('/base/tables');
        $response->assertStatus(403);
    }

    public function testBaseTablesActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/tables');
        $response->assertStatus(200);
    }

    public function testBaseTabs(): void
    {
        $response = $this->get('/base/tabs');
        $response->assertStatus(403);
    }

    public function testBaseTabsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/tabs');
        $response->assertStatus(200);
    }

    public function testBaseTooltips(): void
    {
        $response = $this->get('/base/tooltips');
        $response->assertStatus(403);
    }

    public function testBaseTooltipsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/base/tooltips');
        $response->assertStatus(200);
    }

/* #################   BUTTONS   ###################  */
    public function testButtonsButtons(): void
    {
        $response = $this->get('/buttons/buttons');
        $response->assertStatus(403);
    }

    public function testButtonsButtonsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/buttons/buttons');
        $response->assertStatus(200);
    }

    public function testButtonsButtongroup(): void
    {
        $response = $this->get('/buttons/button-group');
        $response->assertStatus(403);
    }

    public function testButtonsButtonsgroupActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/buttons/button-group');
        $response->assertStatus(200);
    }

    public function testButtonsDropdowns(): void
    {
        $response = $this->get('/buttons/dropdowns');
        $response->assertStatus(403);
    }

    public function testButtonsDropdownsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/buttons/dropdowns');
        $response->assertStatus(200);
    }

    public function testBrandButtons(): void
    {
        $response = $this->get('/buttons/brand-buttons');
        $response->assertStatus(403);
    }

    public function testBrandButtonsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/buttons/brand-buttons');
        $response->assertStatus(200);
    }

/*  ##################    CHARTS    ################ */

    public function testCharts(): void
    {
        $response = $this->get('/charts');
        $response->assertStatus(403);
    }

    public function testChartsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/charts');
        $response->assertStatus(200);
    }

/*  #################    ICONS    ################# */
    public function testIconsCoreuiIcons(): void
    {
        $response = $this->get('/icon/coreui-icons');
        $response->assertStatus(403);
    }

    public function testIconsCoreuiIconsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/icon/coreui-icons');
        $response->assertStatus(200);
    }

    public function testIconsFlags(): void
    {
        $response = $this->get('/icon/flags');
        $response->assertStatus(403);
    }

    public function testIconsFlagsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/icon/flags');
        $response->assertStatus(200);
    }

    public function testIconsBrands(): void
    {
        $response = $this->get('/icon/brands');
        $response->assertStatus(403);
    }

    public function testIconsBrandsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/icon/brands');
        $response->assertStatus(200);
    }

/*  ###############    NOTIFICATIONS    ################# */
    public function testNotificationsAlerts(): void
    {
        $response = $this->get('/notifications/alerts');
        $response->assertStatus(403);
    }

    public function testNotificationsAlertsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/notifications/alerts');
        $response->assertStatus(200);
    }

    public function testNotificationsBadge(): void
    {
        $response = $this->get('/notifications/badge');
        $response->assertStatus(403);
    }

    public function testNotificationsBadgeActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/notifications/badge');
        $response->assertStatus(200);
    }

    public function testNotificationsModals(): void
    {
        $response = $this->get('/notifications/modals');
        $response->assertStatus(403);
    }

    public function testNotificationsModalsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/notifications/modals');
        $response->assertStatus(200);
    }

/*  ##############   WIDGETS   ###############  */
    public function testWidgets(): void
    {
        $response = $this->get('/widgets');
        $response->assertStatus(403);
    }

    public function testWidgetsActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/widgets');
        $response->assertStatus(200);
    }

/*  ##############    PAGES    ############### */
    public function testLogin(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testRegister(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test404(): void
    {
        $response = $this->get('/404');
        $response->assertStatus(403);
    }

    public function test404ActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/404');
        $response->assertStatus(200);
    }

    public function test500(): void
    {
        $response = $this->get('/500');
        $response->assertStatus(403);
    }

    public function test500ActingAsUser(): void
    {
        $user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $user->assignRole($roleUser);
        $response = $this->actingAs($user)->get('/500');
        $response->assertStatus(200);
    }
}

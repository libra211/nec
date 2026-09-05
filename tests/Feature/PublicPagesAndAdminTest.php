<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\Country;
use App\Models\DiasporaMission;
use Tests\TestCase;

class PublicPagesAndAdminTest extends TestCase
{
    /* ─── Public CMS pages ─────────────────────────── */

    public function test_published_page_renders()
    {
        $page = CmsPage::create([
            'title' => 'Test Page',
            'slug' => 'test-page-' . uniqid(),
            'content' => '<h1>Hello from CMS</h1>',
            'status' => 'published',
        ]);

        $response = $this->get(route('pages.show', $page->slug));
        $response->assertOk();
        $response->assertSee('Test Page');
    }

    public function test_draft_page_returns_404()
    {
        $page = CmsPage::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page-' . uniqid(),
            'content' => 'nope',
            'status' => 'draft',
        ]);

        $response = $this->get(route('pages.show', $page->slug));
        $response->assertNotFound();
    }

    public function test_missing_page_returns_404()
    {
        $response = $this->get(route('pages.show', 'does-not-exist-' . uniqid()));
        $response->assertNotFound();
    }

    /* ─── Admin country CRUD ───────────────────────── */

    private function adminSession()
    {
        $this->withSession([
            'admin_logged_in' => true,
            'admin_user_id' => 1,
            'admin_user_name' => 'Test Admin',
            'admin_role' => 'super_admin',
        ]);
    }

    private function uniqueCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
        } while (Country::where('code', $code)->exists());
        return $code;
    }

    public function test_admin_countries_index_requires_auth()
    {
        $this->get(route('admin.countries.index'))->assertRedirect(route('login'));
    }

    public function test_admin_country_crud_lifecycle()
    {
        $this->adminSession();
        $code = $this->uniqueCode();

        $this->get(route('admin.countries.index'))->assertOk();
        $this->get(route('admin.countries.create'))->assertOk();

        $this->post(route('admin.countries.store'), [
            'name' => 'Crud Country ' . $code,
            'code' => $code,
            'iso3' => 'CRU',
            'nationality' => 'Crudanese',
            'continent' => 'Testland',
            'calling_code' => '+999',
            'status' => 'active',
        ])->assertRedirect(route('admin.countries.index'));

        $country = Country::where('code', $code)->first();
        $this->assertNotNull($country);

        $this->get(route('admin.countries.edit', $country->id))->assertOk();

        $this->put(route('admin.countries.update', $country->id), [
            'name' => 'Crud Country Updated ' . $code,
            'code' => $code,
            'iso3' => 'CRU',
            'nationality' => 'Crudanese',
            'continent' => 'Testland',
            'calling_code' => '+999',
            'status' => 'inactive',
        ])->assertRedirect(route('admin.countries.index'));

        $country->refresh();
        $this->assertEquals('inactive', $country->status);

        $this->delete(route('admin.countries.destroy', $country->id))->assertRedirect();
        $this->assertNull(Country::find($country->id));
    }

    public function test_admin_country_in_use_is_deactivated_not_deleted()
    {
        $country = Country::create([
            'name' => 'In Use Country',
            'code' => $this->uniqueCode(),
            'iso3' => 'IUC',
            'status' => 'active',
        ]);
        DiasporaMission::create([
            'country_id' => $country->id,
            'name' => 'Mission for In Use',
            'code' => 'M' . substr(uniqid(), -7),
            'status' => 'active',
        ]);

        $this->adminSession();

        $this->delete(route('admin.countries.destroy', $country->id))->assertRedirect();
        $country->refresh();
        $this->assertEquals('inactive', $country->status);
        $this->assertNotNull(Country::find($country->id));
    }

    /* ─── Admin diaspora mission CRUD ──────────────── */

    public function test_admin_mission_crud_lifecycle()
    {
        $country = Country::create([
            'name' => 'Mission Country',
            'code' => $this->uniqueCode(),
            'iso3' => 'MNC',
            'status' => 'active',
        ]);

        $this->adminSession();

        $rt = random_int(1000, 9999);
        $this->post(route('admin.diaspora-missions.store'), [
            'country_id' => $country->id,
            'name' => 'Mission Crud ' . $rt,
            'city' => 'London',
            'address' => '1 Strand',
            'code' => 'MC' . $rt,
            'phone' => '+442071231234',
            'email' => 'mission' . $rt . '@example.com',
            'status' => 'active',
        ])->assertRedirect(route('admin.diaspora-missions.index'));

        $mission = DiasporaMission::where('code', 'MC' . $rt)->first();
        $this->assertNotNull($mission);

        $this->get(route('admin.diaspora-missions.edit', $mission->id))->assertOk();

        $this->put(route('admin.diaspora-missions.update', $mission->id), [
            'country_id' => $country->id,
            'name' => 'Mission Crud Updated ' . $rt,
            'city' => 'London',
            'address' => '2 Strand',
            'code' => 'MC' . $rt,
            'status' => 'inactive',
        ])->assertRedirect(route('admin.diaspora-missions.index'));

        $mission->refresh();
        $this->assertEquals('inactive', $mission->status);

        $this->delete(route('admin.diaspora-missions.destroy', $mission->id))->assertRedirect();
        $this->assertNotNull(DiasporaMission::withTrashed()->find($mission->id));
        $this->assertSoftDeleted('nec_diaspora_missions', ['id' => $mission->id]);
    }

    public function test_admin_mission_restore()
    {
        $country = Country::create([
            'name' => 'Restore Country',
            'code' => $this->uniqueCode(),
            'iso3' => 'RSC',
            'status' => 'active',
        ]);
        $mission = DiasporaMission::create([
            'country_id' => $country->id,
            'name' => 'To Restore',
            'code' => 'M' . substr(uniqid(), -7),
            'status' => 'inactive',
        ]);

        $this->adminSession();
        $this->delete(route('admin.diaspora-missions.destroy', $mission->id))->assertRedirect();

        $this->get(route('admin.diaspora-missions.restore', $mission->id))->assertRedirect();
        $mission->refresh();
        $this->assertEquals('active', $mission->status);
    }
}
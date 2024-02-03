<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource;

use Playground\Test\Models\UserWithSanctum;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\ShowTrait
 */
trait ShowTrait
{
    public function test_guest_cannot_render_show_view()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->get($url);
        $response->assertRedirect(route('login'));
    }

    public function test_show_view_rendered_by_user()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = UserWithSanctum::factory()->create();

        $index = route($this->packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_show_info_with_user_using_json()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = UserWithSanctum::factory()->create();

        $url = route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        // $response->dump();

        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
        $this->assertAuthenticated();
    }
}

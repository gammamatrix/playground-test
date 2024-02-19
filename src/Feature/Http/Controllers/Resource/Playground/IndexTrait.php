<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\IndexTrait
 */
trait IndexTrait
{
    public function test_guest_cannot_render_index_view()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route($this->packageInfo['model_route'], [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->get($url);
        $response->assertStatus(403);
        // $response->assertRedirect(route('login'));
    }

    public function test_index_view_rendered_by_admin()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($this->packageInfo['model_route'], [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_index_as_admin_using_json()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($this->packageInfo['model_route'], [
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

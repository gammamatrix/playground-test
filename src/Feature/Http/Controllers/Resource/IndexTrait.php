<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Feature\Http\Controllers\Resource;

use GammaMatrix\Playground\Test\Models\User;
use GammaMatrix\Playground\Test\Models\UserWithSanctum;
use Tests\Feature\GammaMatrix\Playground\Matrix\Resource\TestCase;

/**
 * \GammaMatrix\Playground\Test\Feature\Http\Controllers\Resource\IndexTrait
 *
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
        $response->assertRedirect(route('login'));
    }

    public function test_index_view_rendered_by_user()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = UserWithSanctum::factory()->create();

        $url = route($this->packageInfo['model_route'], [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_index_info_with_user_using_json()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = UserWithSanctum::factory()->create();

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

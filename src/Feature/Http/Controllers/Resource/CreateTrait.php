<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Feature\Http\Controllers\Resource;

use GammaMatrix\Playground\Test\Models\User;
use Tests\Feature\GammaMatrix\Playground\Matrix\Resource\TestCase;

/**
 * \GammaMatrix\Playground\Test\Feature\Http\Controllers\Resource\CreateTrait
 *
 */
trait CreateTrait
{
    public function test_guest_cannot_render_create_view()
    {
        $url = route(sprintf(
            '%1$s.create',
            $this->packageInfo['model_route']
        ));

        $response = $this->get($url);
        $response->assertRedirect(route('login'));
    }

    public function test_create_view_rendered_by_user_with_return_url()
    {
        $user = User::factory()->create();

        $index = route($this->packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.create',
            $this->packageInfo['model_route']
        ), [
            '_return_url' => $index,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();

        $response->assertSee(sprintf(
            '<input type="hidden" name="_return_url" value="%1$s">',
            $index
        ), false);
    }

    public function test_create_info_with_user_using_json()
    {
        $user = User::factory()->create();

        $url = route(sprintf(
            '%1$s.create',
            $this->packageInfo['model_route']
        ));

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        // $response->dump();

        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
        $this->assertAuthenticated();
    }

    public function test_create_view_rendered_by_user_with_invalid_parameter()
    {
        $user = User::factory()->create();

        $url = route(sprintf(
            '%1$s.create',
            $this->packageInfo['model_route']
        ));

        $response = $this->actingAs($user)
            ->from($url)
            ->get($url.'?owned_by_id=[duck]')
        ;

        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
        $response->assertRedirect($url);

        // // The owned by id field must be a valid UUID.
        $response->assertSessionHasErrors([
            'owned_by_id',
        ]);

        $this->assertAuthenticated();
    }
}

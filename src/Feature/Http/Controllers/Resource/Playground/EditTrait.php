<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\EditTrait
 */
trait EditTrait
{
    public function test_guest_cannot_render_edit_view()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->get($url);
        // $response->assertRedirect(route('login'));
        $response->assertStatus(403);
    }

    public function test_edit_as_admin_view_rendered_by_user_with_return_url()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $index = route($this->packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.edit',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
            '_return_url' => $index,
        ]);
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        //     '$model' => $model->toArray(),
        //     '$user' => $user->toArray(),
        // ]);
        $response = $this->actingAs($user)->get($url);

        $this->assertAuthenticated();
        // $response->dump();
        $response->assertStatus(200);

        $response->assertSee(sprintf(
            '<input type="hidden" name="_return_url" value="%1$s">',
            $index
        ), false);
    }

    public function test_edit_as_admin_info_with_user_using_json()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
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

    public function test_edit_as_admin_view_rendered_by_user_with_invalid_parameter()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url.'?owned_by_id=[duck]');

        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
        $response->assertStatus(302);

        // // The owned by id field must be a valid UUID.
        $response->assertSessionHasErrors([
            'owned_by_id',
        ]);

        $this->assertAuthenticated();
    }
}

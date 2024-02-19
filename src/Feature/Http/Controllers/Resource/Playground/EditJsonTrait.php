<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\EditJsonTrait
 */
trait EditJsonTrait
{
    public function test_json_guest_cannot_render_edit_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->getJson($url);
        $response->assertStatus(403);
    }

    public function test_json_edit_as_admin_view_rendered_by_user_with_return_url()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $index,
        ]);
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        //     '$model' => $model->toArray(),
        //     '$user' => $user->toArray(),
        // ]);
        $response = $this->actingAs($user)->getJson($url);

        $this->assertAuthenticated();
        // $response->dump();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    public function test_json_edit_as_admin_info_with_user()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
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

    public function test_json_edit_as_admin_view_rendered_by_user_with_invalid_parameter()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->getJson($url.'?owned_by_id=[duck]');

        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
        ]);
        $response->assertJsonValidationErrorFor('owned_by_id');

        $this->assertAuthenticated();
    }
}

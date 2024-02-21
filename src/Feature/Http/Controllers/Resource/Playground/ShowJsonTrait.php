<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\ShowJsonTrait
 */
trait ShowJsonTrait
{
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_json_guest_cannot_see_info()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.show',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->getJson($url);
        $response->assertStatus(403);
    }

    public function test_json_show_info_for_admin()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.show',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();

        $response->assertJsonStructure($this->getStructureData());
    }
}

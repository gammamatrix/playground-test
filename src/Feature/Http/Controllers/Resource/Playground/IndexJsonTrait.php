<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\IndexJsonTrait
 */
trait IndexJsonTrait
{
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureIndex(): array;

    public function test_json_guest_cannot_get_index()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route($packageInfo['model_route'], [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->getJson($url);
        $response->assertStatus(403);
    }

    public function test_json_admin_can_get_index()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($packageInfo['model_route'], [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        // $response->dump();

        $response->assertJsonStructure($this->getStructureIndex());

        $this->assertAuthenticated();
    }
}

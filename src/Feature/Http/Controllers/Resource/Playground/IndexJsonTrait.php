<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\IndexJsonTrait
 */
trait IndexJsonTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

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

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route($packageInfo['model_route']);

        $response = $this->getJson($url);
        $response->assertStatus(403);
    }

    public function test_json_admin_can_get_index()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($packageInfo['model_route']);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        // $response->dump();

        $response->assertJsonStructure($this->getStructureIndex());

        $this->assertAuthenticated();
    }
}

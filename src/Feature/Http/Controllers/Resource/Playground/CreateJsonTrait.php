<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\CreateJsonTrait
 */
trait CreateJsonTrait
{
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureCreate(): array;

    public function test_json_guest_cannot_get_info()
    {
        $packageInfo = $this->getPackageInfo();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->getJson($url);
        // $response->dump();
        $response->assertStatus(403);
    }

    public function test_json_guest_cannot_render_create_view_requesting_json()
    {
        $packageInfo = $this->getPackageInfo();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->getJson($url);
        // $response->dump();
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'message',
        ]);
    }

    public function test_json_create_info_with_admin()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        $this->assertAuthenticated();
        // $response->dump();

        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
        $response->assertJsonStructure($this->getStructureCreate());
    }

    public function test_json_create_info_by_admin_with_invalid_parameter()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->actingAs($user)
            ->from($url)
            ->getJson($url.'?owned_by_id=[duck]');

        $this->assertAuthenticated();

        $response->assertStatus(422);
        // $response->dump();
        $response->assertJsonStructure([
            'errors',
        ]);
        $response->assertJsonValidationErrorFor('owned_by_id');
        // $response->dumpHeaders();
        // $response->dumpSession();

    }
}

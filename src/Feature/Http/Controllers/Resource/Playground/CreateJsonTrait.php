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

    public function test_json_guest_cannot_get_create_info()
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

    public function test_json_admin_can_get_create_info()
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

        $response->assertJsonStructure($this->getStructureCreate());
    }

    public function test_json_get_create_info_as_admin_with_invalid_parameter_and_fail_validation()
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

        $response->assertJsonStructure([
            'errors',
        ]);

        $response->assertJsonValidationErrorFor('owned_by_id');
    }
}

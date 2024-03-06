<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\CreateJsonTrait
 */
trait CreateJsonTrait
{
    protected int $status_code_json_guest_create = 403;

    protected string $create_info_parameter = 'owned_by_id';

    protected string $create_info_invalid_value = '[duck]';

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

        $response->assertStatus($this->status_code_json_guest_create);
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

        $response = $this->actingAs($user)->from($url)->getJson(sprintf(
            '%1$s?%2$s=%3$s',
            $url,
            $this->create_info_parameter,
            $this->create_info_invalid_value
        ));

        $this->assertAuthenticated();

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
        ]);

        $response->assertJsonValidationErrorFor($this->create_info_parameter);
    }
}

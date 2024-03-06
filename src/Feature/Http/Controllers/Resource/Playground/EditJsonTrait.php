<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\EditJsonTrait
 */
trait EditJsonTrait
{
    protected int $status_code_json_guest_edit = 403;

    protected string $edit_info_parameter = 'owned_by_id';

    protected string $edit_info_invalid_value = '[duck]';

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
    abstract public function getStructureEdit(): array;

    public function test_json_guest_cannot_get_edit_info()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->getJson($url);

        $response->assertStatus($this->status_code_json_guest_edit);
    }

    public function test_json_admin_can_get_edit_info()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

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

    public function test_json_edit_as_admin_and_fail_validation_with_invalid_parameter()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->from($url)->getJson(sprintf(
            '%1$s?%2$s=%3$s',
            $url,
            $this->edit_info_parameter,
            $this->edit_info_invalid_value
        ));

        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
        ]);
        $response->assertJsonValidationErrorFor($this->edit_info_parameter);

        $this->assertAuthenticated();
    }
}

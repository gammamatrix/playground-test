<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\UpdateJsonTrait
 */
trait UpdateJsonTrait
{
    protected int $status_code_guest_json_update = 403;

    protected string $update_json_parameter = 'title';

    protected array $json_update_without_payload_errors = [
        'title',
    ];

    protected array $json_update_payload = [
        'title' => 'change to new title',
    ];

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_json_guest_cannot_update()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.patch',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->patchJson($url);

        $response->assertStatus($this->status_code_guest_json_update);
    }

    public function test_json_update_as_admin_without_payload_and_fail_validation()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.patch',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->patchJson($url);

        $response->assertInvalid($this->json_update_without_payload_errors);
        $response->assertStatus(422);

        $this->assertAuthenticated();
    }

    public function test_json_admin_can_update()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            $this->update_json_parameter => $model->getAttributeValue($this->update_json_parameter),
        ]);

        $payload = $this->json_update_payload + $model->toArray();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            $this->update_json_parameter => $payload[$this->update_json_parameter],
        ]);

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.patch',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->patchJson($url, $payload);
        // $response->dump();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->getStructureData());

        $this->assertAuthenticated();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            $this->update_json_parameter => $payload[$this->update_json_parameter],
        ]);
    }
}

<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\StoreJsonTrait
 */
trait StoreJsonTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureData(): array;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_json_guest_cannot_store()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'title' => $model->title,
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->postJson($url, $model->toArray());

        $response->assertStatus(403);

        $this->assertDatabaseMissing($packageInfo['table'], [
            'title' => $model->title,
        ]);
    }

    protected array $store_without_payload_errors = [
        'title',
    ];

    public function test_json_store_as_admin_without_payload_and_fail_validation()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'title' => $model->title,
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->actingAs($user)->postJson($url);
        $response->dump();

        $response->assertInvalid($this->store_without_payload_errors);
        $response->assertStatus(422);

        $this->assertDatabaseMissing($packageInfo['table'], [
            'title' => $model->title,
        ]);
    }

    public function test_json_store_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'title' => $model->title,
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->actingAs($user)->postJson($url, $model->toArray());

        $response->dump();

        $this->assertDatabaseHas($packageInfo['table'], [
            'title' => $model->title,
        ]);

        $created = $fqdn::where('title', $model->title)->firstOrFail();

        $response->assertStatus(201);
    }
}

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
    protected int $status_code_guest_json_store = 403;

    protected string $store_json_parameter = 'title';

    protected array $store_json_without_payload_errors = [
        'title',
    ];

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
            $this->store_json_parameter => $model->getAttributeValue($this->store_json_parameter),
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->postJson($url, $model->toArray());

        $response->assertStatus($this->status_code_guest_json_store);

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_json_parameter => $model->getAttributeValue($this->store_json_parameter),
        ]);
    }

    public function test_json_store_as_admin_without_payload_and_fail_validation()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_json_parameter => $model->getAttributeValue($this->store_json_parameter),
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->actingAs($user)->postJson($url);

        $response->assertInvalid($this->store_json_without_payload_errors);
        $response->assertStatus(422);

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_json_parameter => $model->getAttributeValue($this->store_json_parameter),
        ]);
    }

    public function test_json_store_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$model->toArray()' => $model->toArray(),
        //     '$this->store_json_parameter' => $this->store_json_parameter,
        //     '$model->getAttributeValue($this->store_json_parameter)' => $model->getAttributeValue($this->store_json_parameter),
        // ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_json_parameter => $model->getAttributeValue($this->store_json_parameter),
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        // ]);

        $response = $this->actingAs($user)->postJson($url, $model->toArray());
        // $response->dump();

        $this->assertDatabaseHas($packageInfo['table'], [
            $this->store_json_parameter => $model->getAttributeValue($this->store_json_parameter),
        ]);

        $created = $fqdn::where(
            $this->store_json_parameter,
            $model->getAttributeValue($this->store_json_parameter)
        )->firstOrFail();

        $response->assertStatus(201);
    }
}

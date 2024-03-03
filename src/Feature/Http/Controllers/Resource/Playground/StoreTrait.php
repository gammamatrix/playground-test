<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\StoreTrait
 */
trait StoreTrait
{
    protected int $status_code_guest_store = 403;

    protected string $store_parameter = 'title';

    protected array $store_without_payload_errors = [
        'title',
    ];

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_store()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->post($url, $model->toArray());

        $response->assertStatus($this->status_code_guest_store);

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);
    }

    public function test_store_as_admin_without_payload_and_fail_validation()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));

        $response = $this->actingAs($user)->post($url);
        // $response->dump();
        // $response->dumpSession();

        $response->assertInvalid($this->store_without_payload_errors);
        $response->assertStatus(302);

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);
        // $response->assertStatus(422);
    }

    public function test_store_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$model->toArray()' => $model->toArray(),
        //     '$this->store_json_parameter' => $this->store_parameter,
        //     '$model->getAttributeValue($this->store_json_parameter)' => $model->getAttributeValue($this->store_parameter),
        // ]);

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);

        $url = route(sprintf('%1$s.post', $packageInfo['model_route']));
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        // ]);

        $response = $this->actingAs($user)->post($url, $model->toArray());

        // $response->dump();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);

        $created = $fqdn::where(
            $this->store_parameter,
            $model->getAttributeValue($this->store_parameter)
        )->firstOrFail();

        $response->assertRedirect(route(sprintf('%1$s.show', $packageInfo['model_route']), [
            $packageInfo['model_slug'] => $created->id,
        ]));
    }

    public function test_store_as_admin_and_succeed_with_return_url()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->make();

        $this->assertDatabaseMissing($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);

        $_return_url = route($packageInfo['model_route'], [
            'sort' => '-created_at',
        ]);

        $url = route(sprintf(
            '%1$s.post',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $_return_url,
        ]);

        $response = $this->actingAs($user)->post($url, $model->toArray());

        // $response->dump();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            $this->store_parameter => $model->getAttributeValue($this->store_parameter),
        ]);

        $created = $fqdn::where(
            $this->store_parameter,
            $model->getAttributeValue($this->store_parameter)
        )->firstOrFail();

        $response->assertRedirect($_return_url);
    }
}

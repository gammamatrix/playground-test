<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\UpdateTrait
 */
trait UpdateTrait
{
    protected int $status_code_guest_update = 403;

    protected string $update_parameter = 'title';

    /**
     * @var array<int, string>
     */
    protected array $update_without_payload_errors = [
        'title',
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $update_payload = [
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

    public function test_guest_cannot_update()
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

        $response = $this->patch($url);

        $response->assertStatus($this->status_code_guest_update);
    }

    public function test_update_as_admin_without_payload_and_fail_validation()
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

        $response = $this->actingAs($user)->patch($url);

        $response->assertInvalid($this->update_without_payload_errors);
        $response->assertStatus(302);

        $this->assertAuthenticated();
    }

    public function test_admin_can_update()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            $this->update_parameter => $model->getAttributeValue($this->update_parameter),
        ]);

        $payload = $this->update_payload + $model->toArray();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            $this->update_parameter => $payload[$this->update_parameter],
        ]);

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.patch',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->patch($url, $payload);

        $response->assertRedirect(route(sprintf('%1$s.show', $packageInfo['model_route']), [
            $packageInfo['model_slug'] => $model->id,
        ]));

        $this->assertAuthenticated();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            $this->update_parameter => $payload[$this->update_parameter],
        ]);
    }

    public function test_admin_can_update_view_with_return_url()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            $this->update_parameter => $model->getAttributeValue($this->update_parameter),
        ]);

        $payload = $this->update_payload + $model->toArray();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            $this->update_parameter => $payload[$this->update_parameter],
        ]);

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.patch',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $index,
        ]);

        $response = $this->actingAs($user)->patch($url, $payload);

        $this->assertAuthenticated();

        $response->assertRedirect($index);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            $this->update_parameter => $payload[$this->update_parameter],
        ]);
    }
}

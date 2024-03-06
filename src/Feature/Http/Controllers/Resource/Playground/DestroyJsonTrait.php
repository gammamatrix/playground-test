<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\DestroyJsonTrait
 */
trait DestroyJsonTrait
{
    protected int $status_code_json_guest_destroy = 403;

    protected int $status_code_json_user_destroy = 401;

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_json_guest_cannot_destroy()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->deleteJson($url);

        $response->assertStatus($this->status_code_json_guest_destroy);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);
    }

    public function test_json_destroy_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);
        $response->assertNoContent();
    }

    public function test_json_destroy_as_admin_and_succeed_with_force_delete()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            'force' => true,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_as_admin_and_succeed_with_no_content()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_as_admin_and_succeed_and_ignore_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $_return_url = route($packageInfo['model_route'], [
            'filter' => [
                'trash' => 'with',
            ],
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $_return_url,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_as_user_and_get_denied_and_no_force_delete_allowed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            'force' => true,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $response->assertStatus($this->status_code_json_user_destroy);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);
    }
}

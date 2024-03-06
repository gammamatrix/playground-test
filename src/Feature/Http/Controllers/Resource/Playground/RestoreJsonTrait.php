<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\RestoreJsonTrait
 */
trait RestoreJsonTrait
{
    protected int $status_code_json_guest_restore = 403;

    protected int $status_code_json_user_restore = 401;

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureData(): array;

    public function test_json_guest_cannot_restore()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->putJson($url);

        $response->assertStatus($this->status_code_json_guest_restore);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_json_restore_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_restore_as_admin_and_succeed_with_no_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => Carbon::now(),
        ]);

        $_return_url = route($packageInfo['model_route'], [
            'filter' => [
                'trash' => 'only',
            ],
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $_return_url,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_restore_as_user_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->create(['role' => 'user']);

        $model = $fqdn::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $response->assertStatus($this->status_code_json_user_restore);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}

<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\UnlockJsonTrait
 */
trait UnlockJsonTrait
{
    protected int $status_code_guest_json_unlock = 403;

    protected int $status_code_user_json_unlock = 401;

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
    abstract public function getStructureData(): array;

    public function test_json_guest_cannot_unlock()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create([
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->deleteJson($url);

        $response->assertStatus($this->status_code_guest_json_unlock);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => true,
        ]);
    }

    public function test_json_unlock_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_unlock_as_admin_and_succeed_with_no_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => true,
        ]);

        $_return_url = route($packageInfo['model_route'], [
            'sort' => '-unlocked',
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $_return_url,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_unlock_as_user_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create(['role' => 'user']);

        $model = $fqdn::factory()->create([
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $response->assertStatus($this->status_code_user_json_unlock);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => true,
        ]);
    }
}

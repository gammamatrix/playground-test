<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\LockJsonTrait
 */
trait LockJsonTrait
{
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureData(): array;

    public function test_json_guest_cannot_lock()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->putJson($url);

        $response->assertStatus(403);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => false,
        ]);
    }

    public function test_json_lock_as_admin_user_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);
    }

    public function test_json_lock_as_admin_user_and_succeed_with_no_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        // _return_url should have no effect
        $_return_url = route($packageInfo['model_route'], [
            'sort' => '-locked',
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $_return_url,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_lock_as_user_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->create();
        // $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $response->assertStatus(401);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);
    }

    public function test_json_lock_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_lock_as_admin_and_succeed_with_json()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }
}

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
    public function test_json_guest_cannot_lock()
    {
        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->putJson($url);

        // $response->dump();

        $response->assertStatus(403);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => false,
        ]);
    }

    public function test_json_lock_as_admin_user_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);
    }

    public function test_json_lock_as_admin_user_and_succeed_with_no_redirect()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        // _return_url should have no effect
        $_return_url = route($this->packageInfo['model_route'], [
            'sort' => '-locked',
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
            '_return_url' => $_return_url,
        ]);

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        //     '$_return_url' => $_return_url,
        // ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_lock_with_user_role_and_get_denied()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();
        // $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $response->assertStatus(401);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);
    }

    public function test_json_lock_with_admin_role_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_lock_with_admin_role_and_succeed_with_json()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }
}

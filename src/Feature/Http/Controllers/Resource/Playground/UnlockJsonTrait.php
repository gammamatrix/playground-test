<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\UnlockJsonTrait
 */
trait UnlockJsonTrait
{
    public function test_json_guest_cannot_unlock()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create([
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->deleteJson($url);

        // $response->dump();

        $response->assertStatus(403);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => true,
        ]);
    }

    public function test_json_unlock_as_admin_user_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
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
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_unlock_as_admin_user_and_succeed_with_no_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
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

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        //     '$_return_url' => $_return_url,
        // ]);

        $response = $this->actingAs($user)->deleteJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_unlock_with_user_role_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create(['role' => 'user']);
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$user' => $user,
        // ]);
        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        $response->assertStatus(401);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);
    }

    public function test_json_unlock_with_admin_role_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
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
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }
}

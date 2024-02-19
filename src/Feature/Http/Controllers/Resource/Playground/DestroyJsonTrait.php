<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\DestroyJsonTrait
 */
trait DestroyJsonTrait
{
    public function test_json_guest_cannot_destroy()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
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
            'deleted_at' => null,
        ]);
    }

    public function test_json_destroy_as_admin_user_and_succeed()
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
            'owned_by_id' => $user->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);
        $response->assertNoContent();
    }

    public function test_json_destroy_as_admin_user_and_succeed_with_force_delete()
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

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_as_admin_user_using_json_and_succeed_with_no_content()
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
            'owned_by_id' => $user->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_as_admin_user_and_succeed_with_redirect_to_index_with_trash()
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
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_with_user_role_and_get_denied_and_no_force_delete_allowed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
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

        $response->assertStatus(401);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_json_destroy_with_admin_role_and_succeed()
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
            'owned_by_id' => $user->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertNoContent();
    }

    public function test_json_destroy_with_admin_role_and_succeed_with_force_delete()
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

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
        ]);

        $response->assertNoContent();
    }
}

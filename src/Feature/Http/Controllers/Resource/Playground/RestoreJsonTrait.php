<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Support\Carbon;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\RestoreJsonTrait
 */
trait RestoreJsonTrait
{
    public function test_json_guest_cannot_restore()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->putJson($url);

        // $response->dump();

        // $response->assertRedirect(route('login'));
        $response->assertStatus(403);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_json_restore_as_admin_user_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_restore_as_admin_user_and_succeed_with_no_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
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

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_restore_with_user_role_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->create(['role' => 'user']);

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $response->assertStatus(401);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_json_restore_with_admin_role_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }
}
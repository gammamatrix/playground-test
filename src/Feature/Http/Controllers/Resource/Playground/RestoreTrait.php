<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Support\Carbon;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\RestoreTrait
 */
trait RestoreTrait
{
    public function test_guest_cannot_restore()
    {
        // config([
        //     // 'playground-auth.token.name' => 'app',
        //     'playground-auth.verify' => 'user',
        //     'playground-auth.userRole' => false,
        //     'playground-auth.hasRole' => false,
        //     'playground-auth.userRoles' => false,
        //     'playground-auth.hasPrivilege' => false,
        //     'playground-auth.userPrivileges' => false,
        // ]);

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->put($url);

        // $response->dump();

        // $response->assertRedirect(route('login'));
        $response->assertStatus(403);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_restore_as_admin_user_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $this->packageInfo['model_route']), [
            $this->packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_restore_as_admin_user_using_json_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
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
            'deleted_at' => null,
        ]);

        $response->assertStatus(200);
    }

    public function test_restore_as_admin_user_and_succeed_with_redirect_to_index_with_only_trash()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $_return_url = route($this->packageInfo['model_route'], [
            'filter' => [
                'trash' => 'only',
            ],
        ]);

        $url = route(sprintf(
            '%1$s.restore',
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

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_restore_with_user_role_and_get_denied()
    {
        // config([
        //     'playground-auth.verify' => 'roles',
        //     'playground-auth.userRole' => true,
        //     'playground-auth.hasRole' => true,
        //     'playground-auth.userRoles' => false,
        // ]);

        $fqdn = $this->fqdn;

        $user = User::factory()->create(['role' => 'user']);

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->put($url);

        $response->assertStatus(401);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_restore_with_admin_role_and_succeed()
    {
        // config([
        //     'playground-auth.verify' => 'roles',
        //     'playground-auth.userRole' => true,
        //     'playground-auth.hasRole' => true,
        //     'playground-auth.userRoles' => false,
        // ]);

        $fqdn = $this->fqdn;

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => Carbon::now(),
        ]);

        $url = route(sprintf(
            '%1$s.restore',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $this->packageInfo['model_route']), [
            $this->packageInfo['model_slug'] => $model->id,
        ]));
    }
}

<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\LockTrait
 */
trait LockTrait
{
    public function test_guest_cannot_lock()
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

        $response = $this->put($url);

        // $response->dump();

        // $response->assertRedirect(route('login'));
        $response->assertStatus(403);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => false,
        ]);
    }

    public function test_lock_as_admin_user_and_succeed()
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

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $this->packageInfo['model_route']), [
            $this->packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_lock_as_admin_user_using_json_and_succeed()
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

    public function test_lock_as_admin_user_and_succeed_with_redirect_to_index_with_sorted_by_locked_desc()
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

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_lock_with_user_role_and_get_denied()
    {
        // config([
        //     'playground-auth.verify' => 'roles',
        //     'playground-auth.userRole' => true,
        //     'playground-auth.hasRole' => true,
        //     'playground-auth.userRoles' => false,
        // ]);

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

        $response = $this->actingAs($user)->put($url);

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

    public function test_lock_with_admin_role_and_succeed()
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

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $this->packageInfo['model_route']), [
            $this->packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_lock_with_admin_role_and_succeed_with_json()
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

        $response->assertJsonStructure($this->structure_data);
    }
}

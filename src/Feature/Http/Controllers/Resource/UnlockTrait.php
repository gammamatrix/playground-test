<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource;

use Playground\Test\Models\User;
use Playground\Test\Models\UserWithRole;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\UnlockTrait
 */
trait UnlockTrait
{
    public function test_guest_cannot_unlock()
    {
        config([
            // 'playground.auth.token.name' => 'app',
            'playground.auth.verify' => 'user',
            'playground.auth.userRole' => false,
            'playground.auth.hasRole' => false,
            'playground.auth.userRoles' => false,
            'playground.auth.hasPrivilege' => false,
            'playground.auth.userPrivileges' => false,
        ]);

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create([
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->delete($url);

        // $response->dump();

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => true,
        ]);
    }

    public function test_unlock_as_standard_user_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->delete($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $this->packageInfo['model_route']), [
            $this->packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_unlock_as_standard_user_using_json_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);
    }

    public function test_unlock_as_standard_user_and_succeed_with_redirect_to_index_with_sorted_by_unlocked_desc()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $_return_url = route($this->packageInfo['model_route'], [
            'sort' => '-unlocked',
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
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

        $response = $this->actingAs($user)->delete($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_unlock_with_user_role_and_get_denied()
    {
        config([
            'playground.auth.verify' => 'roles',
            'playground.auth.userRole' => true,
            'playground.auth.hasRole' => true,
            'playground.auth.userRoles' => false,
        ]);

        $fqdn = $this->fqdn;

        $user = UserWithRole::find(User::factory()->create()->id);

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->delete($url);

        $response->assertStatus(401);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);
    }

    public function test_unlock_with_admin_role_and_succeed()
    {
        config([
            'playground.auth.verify' => 'roles',
            'playground.auth.userRole' => true,
            'playground.auth.hasRole' => true,
            'playground.auth.userRoles' => false,
        ]);

        $fqdn = $this->fqdn;

        $user = UserWithRole::find(User::factory()->create()->id);

        // The role is not saved since the column may not exist.
        $user->role = 'admin';

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->delete($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $this->packageInfo['model_route']), [
            $this->packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_unlock_with_admin_role_and_succeed_with_json()
    {
        config([
            'playground.auth.verify' => 'roles',
            'playground.auth.userRole' => true,
            'playground.auth.hasRole' => true,
            'playground.auth.userRoles' => false,
        ]);

        $fqdn = $this->fqdn;

        $user = UserWithRole::find(User::factory()->create()->id);

        // The role is not saved since the column may not exist.
        $user->role = 'admin';

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $url = route(sprintf(
            '%1$s.unlock',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->deleteJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->structure_data);
    }
}

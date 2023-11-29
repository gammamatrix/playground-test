<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Feature\Http\Controllers\Resource;

use GammaMatrix\Playground\Test\Models\User;
use GammaMatrix\Playground\Test\Models\UserWithRole;

/**
 * \GammaMatrix\Playground\Test\Feature\Http\Controllers\Resource\DestroyTrait
 *
 */
trait DestroyTrait
{
    public function test_destroy_as_guest_and_fail_authorization()
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

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
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
            'deleted_at' => null,
        ]);
    }

    public function test_destroy_as_standard_user_and_succeed()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
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
        ]);
        $this->assertDatabaseMissing($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect(route($this->packageInfo['model_route']));
    }

    public function test_destroy_as_standard_user_and_succeed_with_force_delete()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
            'force' => true,
        ]);

        $response = $this->actingAs($user)->delete($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseMissing($this->packageInfo['table'], [
            'id' => $model->id,
        ]);

        $response->assertRedirect(route($this->packageInfo['model_route']));
    }

    public function test_destroy_as_standard_user_using_json_and_succeed_with_no_content()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
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
        ]);
        $this->assertDatabaseMissing($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertNoContent();
    }

    public function test_destroy_as_standard_user_and_succeed_with_redirect_to_index_with_trash()
    {
        $fqdn = $this->fqdn;

        $user = User::factory()->create();

        $model = $fqdn::factory()->create([
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $_return_url = route($this->packageInfo['model_route'], [
            'filter' => [
                'trash' => 'with',
            ]
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
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
        ]);
        $this->assertDatabaseMissing($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_destroy_as_role_user_get_denied_and_no_force_delete_allowed()
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
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
            'force' => true,
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
            'deleted_at' => null,
        ]);
    }

    public function test_destroy_as_role_admin_and_succeed()
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
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
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
        ]);
        $this->assertDatabaseMissing($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect(route($this->packageInfo['model_route']));
    }

    public function test_destroy_as_role_admin_and_succeed_with_force_delete()
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
        ]);

        $this->assertDatabaseHas($this->packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $url = route(sprintf(
            '%1$s.destroy',
            $this->packageInfo['model_route']
        ), [
            $this->packageInfo['model_slug'] => $model->id,
            'force' => true,
        ]);

        $response = $this->actingAs($user)->delete($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseMissing($this->packageInfo['table'], [
            'id' => $model->id,
        ]);

        $response->assertRedirect(route($this->packageInfo['model_route']));
    }
}

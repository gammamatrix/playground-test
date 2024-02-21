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
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_lock()
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

        $response = $this->put($url);

        $response->assertStatus(403);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'locked' => false,
        ]);
    }

    public function test_lock_as_admin_user_and_succeed()
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

        $response = $this->actingAs($user)->put($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $packageInfo['model_route']), [
            $packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_lock_as_admin_and_succeed_with_redirect_to_index_with_sorted_by_locked_desc()
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

        $response = $this->actingAs($user)->put($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_lock_as_user_and_get_denied()
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
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->put($url);

        $response->assertStatus(401);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => false,
        ]);
    }

    public function test_lock_as_admin_and_succeed()
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

        $response = $this->actingAs($user)->put($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'locked' => true,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $packageInfo['model_route']), [
            $packageInfo['model_slug'] => $model->id,
        ]));
    }
}

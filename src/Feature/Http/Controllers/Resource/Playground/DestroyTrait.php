<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\DestroyTrait
 */
trait DestroyTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_destroy()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

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

        $response = $this->delete($url);

        $response->assertStatus(403);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => null,
            'deleted_at' => null,
        ]);
    }

    public function test_destroy_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

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

        $response = $this->actingAs($user)->delete($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
        ]);

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect(route($packageInfo['model_route']));
    }

    public function test_destroy_as_admin_and_succeed_with_force_delete()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

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

        $response = $this->actingAs($user)->delete($url);

        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
        ]);

        $response->assertRedirect(route($packageInfo['model_route']));
    }

    public function test_destroy_as_admin_and_succeed_with_redirect_to_index_with_trash()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

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

        $response = $this->actingAs($user)->delete($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
        ]);
        $this->assertDatabaseMissing($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_destroy_as_user_and_get_denied_and_no_force_delete_allowed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

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

        $response = $this->actingAs($user)->delete($url);

        $response->assertStatus(401);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'owned_by_id' => $user->id,
            'deleted_at' => null,
        ]);
    }
}

<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\LockTrait
 */
trait LockTrait
{
    protected int $status_code_guest_lock = 403;

    protected int $status_code_user_lock = 401;

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_lock()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->put($url);

        $response->assertStatus($this->status_code_guest_lock);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => false,
        ]);
    }

    public function test_lock_as_admin_and_succeed()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
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
            'locked' => true,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $packageInfo['model_route']), [
            $packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_lock_as_admin_and_succeed_with_redirect_to_index_with_sorted_by_locked_desc()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->admin()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
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
            'locked' => true,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_lock_as_user_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $user = User::factory()->create();

        $model = $fqdn::factory()->create();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => false,
        ]);

        $url = route(sprintf(
            '%1$s.lock',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->put($url);

        $response->assertStatus($this->status_code_user_lock);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'locked' => false,
        ]);
    }
}

<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\IndexTrait
 */
trait IndexTrait
{
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_render_index_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $url = route($packageInfo['model_route'], [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->get($url);

        $response->assertStatus(403);
    }

    public function test_admin_can_render_index_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->fqdn;

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($packageInfo['model_route'], [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }
}

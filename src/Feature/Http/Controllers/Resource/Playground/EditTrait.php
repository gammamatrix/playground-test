<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\EditTrait
 */
trait EditTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_render_edit_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->get($url);

        $response->assertStatus(403);
    }

    public function test_admin_can_render_edit_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_admin_can_render_edit_view_with_return_url()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            '_return_url' => $index,
        ]);

        $response = $this->actingAs($user)->get($url);

        $this->assertAuthenticated();

        $response->assertStatus(200);

        $response->assertSee(sprintf(
            '<input type="hidden" name="_return_url" value="%1$s">',
            $index
        ), false);
    }

    public function test_edit_view_as_admin_with_invalid_parameter_and_fail_validation_and_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.edit',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->get($url.'?owned_by_id=[duck]');

        $response->assertStatus(302);

        // // The owned by id field must be a valid UUID.
        $response->assertSessionHasErrors([
            'owned_by_id',
        ]);

        $this->assertAuthenticated();
    }
}

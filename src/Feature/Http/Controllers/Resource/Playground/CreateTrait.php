<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\CreateTrait
 */
trait CreateTrait
{
    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_render_create_view()
    {
        $packageInfo = $this->getPackageInfo();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->get($url);
        $response->assertStatus(403);
    }

    public function test_admin_can_render_create_view()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_admin_can_render_create_view_with_return_url()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ), [
            '_return_url' => $index,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();

        $response->assertSee(sprintf(
            '<input type="hidden" name="_return_url" value="%1$s">',
            $index
        ), false);
    }

    public function test_create_view_as_admin_with_invalid_parameter_and_fail_validation_and_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.create',
            $packageInfo['model_route']
        ));

        $response = $this->actingAs($user)
            ->from($url)
            ->get($url.'?owned_by_id=[duck]');

        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
        $response->assertRedirect($url);

        // // The owned by id field must be a valid UUID.
        $response->assertSessionHasErrors([
            'owned_by_id',
        ]);

        $this->assertAuthenticated();
    }
}

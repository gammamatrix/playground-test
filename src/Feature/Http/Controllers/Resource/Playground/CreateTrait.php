<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\CreateTrait
 */
trait CreateTrait
{
    protected int $status_code_guest_create = 403;

    protected string $create_form_parameter = 'owned_by_id';

    protected string $create_form_invalid_value = '[duck]';

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

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

        $response->assertStatus($this->status_code_guest_create);
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

        $response = $this->actingAs($user)->from($url)->get(sprintf(
            '%1$s?%2$s=%3$s',
            $url,
            $this->create_form_parameter,
            $this->create_form_invalid_value
        ));

        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();
        $response->assertRedirect($url);

        // // The owned by id field must be a valid UUID.
        $response->assertSessionHasErrors([
            $this->create_form_parameter,
        ]);

        $this->assertAuthenticated();
    }
}

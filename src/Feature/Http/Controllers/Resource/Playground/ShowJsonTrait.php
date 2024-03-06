<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\ShowJsonTrait
 */
trait ShowJsonTrait
{
    protected int $status_code_json_guest_show = 403;

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_json_guest_cannot_see_info()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route(sprintf(
            '%1$s.show',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->getJson($url);

        $response->assertStatus($this->status_code_json_guest_show);
    }

    public function test_json_show_info_for_admin()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.show',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
        ]);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();

        $response->assertJsonStructure($this->getStructureData());
    }
}

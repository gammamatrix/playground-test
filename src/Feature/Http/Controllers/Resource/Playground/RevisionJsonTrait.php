<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\RevisionJsonTrait
 */
trait RevisionJsonTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdnRevision(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    abstract public function getRevisionId(): string;

    abstract public function getRevisionRouteParameter(): string;

    public function test_json_guest_cannot_render_revision_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();
        $fqdn_revision = $this->getGetFqdnRevision();

        $model = $fqdn::factory()->create();

        $revision = $fqdn_revision::factory()->create([
            $this->getRevisionId() => $model->id,
            'revision' => 10,
        ]);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 0,
        ]);

        $url = route(sprintf(
            '%1$s.revision',
            $packageInfo['model_route']
        ), [
            $this->getRevisionRouteParameter() => $revision->id,
        ]);

        $response = $this->getJson($url);

        $response->assertStatus(403);
    }

    public function test_json_admin_can_get_revision_info()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();
        $fqdn_revision = $this->getGetFqdnRevision();

        $model = $fqdn::factory()->create();

        $revision = $fqdn_revision::factory()->create([
            $this->getRevisionId() => $model->id,
            'revision' => 10,
        ]);

        $user = User::factory()->admin()->create();

        $index = route($packageInfo['model_route']);

        $url = route(sprintf(
            '%1$s.revision',
            $packageInfo['model_route']
        ), [
            $this->getRevisionRouteParameter() => $revision->id,
        ]);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());

        $this->assertAuthenticated();
    }
}

<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Tests\Feature\Playground\Cms\Resource\Http\Controllers\RestoreRevisionJsonTrait
 */
trait RestoreRevisionJsonTrait
{
    protected int $status_code_json_guest_restore_revision = 403;

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

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureData(): array;

    public function test_json_guest_cannot_restore_revision()
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
            '%1$s.revision.restore',
            $packageInfo['model_route']
        ), [
            $this->getRevisionRouteParameter() => $revision->id,
        ]);

        $response = $this->putJson($url);

        $response->assertStatus($this->status_code_json_guest_restore_revision);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 0,
        ]);
    }

    public function test_json_restore_revision_as_admin_and_succeed()
    {
        $user = User::factory()->admin()->create();

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
            '%1$s.revision.restore',
            $packageInfo['model_route']
        ), [
            $this->getRevisionRouteParameter() => $revision->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 11,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_restore_revision_as_admin_and_succeed_without_redirect()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->admin()->create();

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

        $_return_url = route($packageInfo['model_route'], [
            'filter' => [
                'trash' => 'only',
            ],
        ]);

        $url = route(sprintf(
            '%1$s.revision.restore',
            $packageInfo['model_route']
        ), [
            $this->getRevisionRouteParameter() => $revision->id,
            '_return_url' => $_return_url,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 11,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure($this->getStructureData());
    }

    public function test_json_restore_revision_as_user_and_get_denied()
    {
        $packageInfo = $this->getPackageInfo();

        $user = User::factory()->create(['role' => 'user']);

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
            '%1$s.revision.restore',
            $packageInfo['model_route']
        ), [
            $this->getRevisionRouteParameter() => $revision->id,
        ]);

        $response = $this->actingAs($user)->putJson($url);

        $response->assertStatus(401);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 0,
        ]);
    }
}

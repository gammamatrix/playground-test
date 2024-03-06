<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Tests\Feature\Playground\Cms\Resource\Http\Controllers\RestoreRevisionTrait
 */
trait RestoreRevisionTrait
{
    protected int $status_code_guest_restore_revision = 403;

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

    public function test_guest_cannot_restore_revision()
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

        $response = $this->put($url);

        $response->assertStatus($this->status_code_guest_restore_revision);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 0,
        ]);
    }

    public function test_restore_revision_as_admin_and_succeed()
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

        $response = $this->actingAs($user)->put($url);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 11,
        ]);

        $response->assertRedirect(route(sprintf('%1$s.show', $packageInfo['model_route']), [
            $packageInfo['model_slug'] => $model->id,
        ]));
    }

    public function test_restore_revision_as_admin_and_succeed_with_redirect_to_index_with_only_trash()
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

        $response = $this->actingAs($user)->put($url);

        // $response->dd();
        // $response->dump();
        // $response->dumpHeaders();
        // $response->dumpSession();

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 11,
        ]);

        $response->assertRedirect($_return_url);
    }

    public function test_restore_revision_as_user_and_get_denied()
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

        $response = $this->actingAs($user)->put($url);

        $response->assertStatus(401);

        $this->assertDatabaseHas($packageInfo['table'], [
            'id' => $model->id,
            'revision' => 0,
        ]);
    }
}

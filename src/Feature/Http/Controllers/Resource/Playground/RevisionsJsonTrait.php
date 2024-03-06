<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\RevisionsJsonTrait
 */
trait RevisionsJsonTrait
{
    protected int $status_code_guest_json_revisions = 403;

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

    public function test_json_guest_cannot_get_revisions()
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
            '%1$s.revisions',
            $packageInfo['model_route']
        ), $model);

        $response = $this->getJson($url);

        $response->assertStatus($this->status_code_guest_json_revisions);
    }

    public function test_json_admin_can_get_revisions()
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

        $url = route(sprintf(
            '%1$s.revisions',
            $packageInfo['model_route']
        ), $model);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    protected array $revisions_json_with_filters = [
        'active' => true,
        'created_at' => [
            'operator' => '>=',
            'value' => '-2 weeks midnight',
        ],
        'modified_by_id' => [
            'operator' => 'NULL',
        ],
        'title' => 'revisions with filters',
        'label' => 'revisions_json_with_filters',
    ];

    public function test_json_admin_can_get_revisions_with_filters()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();
        $fqdn_revision = $this->getGetFqdnRevision();

        $model = $fqdn::factory()->create([
            'title' => $this->revisions_json_with_filters['title'],
            'label' => $this->revisions_json_with_filters['label'],
        ]);

        $revision = $fqdn_revision::factory()->create([
            $this->getRevisionId() => $model->id,
            'revision' => 10,
            'title' => $this->revisions_json_with_filters['title'],
            'label' => $this->revisions_json_with_filters['label'],
        ]);

        $user = User::factory()->admin()->create();

        $url = route(sprintf(
            '%1$s.revisions',
            $packageInfo['model_route']
        ), [
            $packageInfo['model_slug'] => $model->id,
            'filter' => $this->revisions_json_with_filters,
        ]);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        // $response->dump();

        $this->assertAuthenticated();

        $response->assertJsonStructure($this->getStructureIndex());

        $response->assertJsonPath('data.0.id', $revision->id);
    }
}

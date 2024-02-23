<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\IndexJsonTrait
 */
trait IndexJsonTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    /**
     * @return array<string, mixed>
     */
    abstract public function getStructureIndex(): array;

    public function test_json_guest_cannot_get_index()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route($packageInfo['model_route']);

        $response = $this->getJson($url);
        $response->assertStatus(403);
    }

    public function test_json_admin_can_get_index()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($packageInfo['model_route']);

        $response = $this->actingAs($user)->getJson($url);

        $response->assertStatus(200);
        // $response->dump();

        $response->assertJsonStructure($this->getStructureIndex());

        $this->assertAuthenticated();
    }

    protected array $index_json_with_filters = [
        'id' => true,
        'active' => true,
        'created_at' => [
            'operator' => '>=',
            'value' => 'last week midnight',
        ],
        'modified_by_id' => [
            'operator' => 'NULL',
        ],
        'title' => 'index json with filters',
        'label' => 'index_json_with_filters',
    ];

    public function test_json_admin_can_get_index_with_filters()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create([
            'title' => $this->index_json_with_filters['title'],
            'label' => $this->index_json_with_filters['label'],
        ]);

        $user = User::factory()->admin()->create();

        if (array_key_exists('id', $this->index_json_with_filters)) {
            if (is_bool($this->index_json_with_filters['id'])) {

                if ($this->index_json_with_filters['id']) {
                    $this->index_json_with_filters['id'] = $model->id;
                } else {
                    unset($this->index_json_with_filters['id']);
                }
            }
        }

        $url = route($packageInfo['model_route'], [
            'filter' => $this->index_json_with_filters,
        ]);

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$url' => $url,
        //     '$this->index_json_with_filters' => $this->index_json_with_filters,
        // ]);

        $response = $this->actingAs($user)->getJson($url);

        // $response->dump();

        $response->assertStatus(200);

        $this->assertAuthenticated();

        // The filters should find the model by ID.
        if (! empty($this->index_json_with_filters['id'])) {
            $response->assertJsonPath('data.0.id', $this->index_json_with_filters['id']);
        }
    }
}

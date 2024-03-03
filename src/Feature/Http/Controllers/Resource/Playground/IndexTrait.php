<?php
/**
 * Playground
 */
namespace Playground\Test\Feature\Http\Controllers\Resource\Playground;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Models\PlaygroundUser as User;

/**
 * \Playground\Test\Feature\Http\Controllers\Resource\Playground\IndexTrait
 */
trait IndexTrait
{
    protected int $status_code_guest_index = 403;

    /**
     * @return class-string<Model>
     */
    abstract public function getGetFqdn(): string;

    /**
     * @return array<string, string>
     */
    abstract public function getPackageInfo(): array;

    public function test_guest_cannot_render_index_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $url = route($packageInfo['model_route']);

        $response = $this->get($url);

        $response->assertStatus($this->status_code_guest_index);
    }

    public function test_admin_can_render_index_view()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create();

        $user = User::factory()->admin()->create();

        $url = route($packageInfo['model_route']);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $this->assertAuthenticated();
    }

    protected array $index_with_filters = [
        'id' => true,
        'active' => true,
        'created_at' => [
            'operator' => '>=',
            'value' => 'yesterday midnight',
        ],
        'modified_by_id' => [
            'operator' => 'NULL',
        ],
        'title' => 'index with filters',
        'label' => 'index_with_filters',
    ];

    public function test_admin_can_render_index_view_with_filters()
    {
        $packageInfo = $this->getPackageInfo();

        $fqdn = $this->getGetFqdn();

        $model = $fqdn::factory()->create([
            'title' => $this->index_with_filters['title'],
            'label' => $this->index_with_filters['label'],
        ]);

        $user = User::factory()->admin()->create();

        if (array_key_exists('id', $this->index_with_filters)) {
            if (is_bool($this->index_with_filters['id'])) {

                if ($this->index_with_filters['id']) {
                    $this->index_with_filters['id'] = $model->id;
                } else {
                    unset($this->index_with_filters['id']);
                }
            }
        }

        $url = route($packageInfo['model_route'], [
            'filter' => $this->index_with_filters,
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);
        // $response->dump();

        $this->assertAuthenticated();

        // The filters should find the model by ID.
        if (! empty($this->index_with_filters['id'])) {
            $response->assertSee($this->index_with_filters['id']);
        }
    }
}

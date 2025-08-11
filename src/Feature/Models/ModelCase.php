<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Playground\Test\Feature\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Playground\Test\Concerns\Factories;
use Playground\Test\OrchestraTestCase;

/**
 * \Playground\Test\Feature\Models\ModelCase
 *
 * NOTE Set the model: protected string $modelClass = Model::class;
 */
abstract class ModelCase extends OrchestraTestCase
{
    use Concerns\HasMany;
    use Concerns\HasOne;
    use DatabaseTransactions;
    use Factories;

    protected bool $debugModels = false;

    /**
     * @var bool A model must be marked as not having relationships.
     *
     * @see testVerifyRelationships()
     */
    protected bool $hasRelationships = true;

    /**
     * @var array<string, array<string, mixed>> Test belongsTo relationships.
     */
    protected array $belongsTo = [
        // 'tag' => ['use' => 'type', 'rule' => '_first', 'modelClass' => \App\Models\Tag::class],
    ];

    /**
     * @var array<string, array<string, mixed>> Test belongsToMany relationships.
     */
    protected array $belongsToMany = [
        // 'tags' => ['use' => 'factory', 'rule' => '_first', 'modelClass' => \App\Models\Tag::class],
    ];

    /**
     * Test relationships: HasMany
     *
     * @var array<string, array{
     *       key: string,
     *       modelClass: class-string<Model>,
     *       options?: array<string, mixed>,
     *       rule?: "create"|"first",
     *       state?: string,
     *       stateHasMany?: string,
     *       optionsHasMany?: array<string, mixed>,
     *       count?: int
     *   }>
     */
    protected array $hasMany = [
        // 'tags' => ['key' => 'coupon_id', 'modelClass' => \App\Models\Tag::class],
    ];

    /**
     * Test relationships: HasOne
     *
     * @var array<string, array{
     *        key: string,
     *        modelClass: class-string<Model>,
     *        options?: array<string, mixed>,
     *        rule?: "create"|"first",
     *        state?: string
     *   }>
     */
    protected array $hasOne = [
        // 'tag' => ['key' => 'coupon_id', 'rule' => 'create', 'modelClass' => \App\Models\Tag::class],
    ];

    /**
     * @var class-string<Model>
     */
    protected string $modelClass = Model::class;

    /**
     * @var bool Verify the relationship model has the expected models.
     *
     * @see testVerifyRelationships()
     */
    protected $verifyRelationshipModel = true;

    protected function getModel(): Model
    {
        $modelClass = $this->getModelClass();

        return new $modelClass;
    }

    /**
     * Get the model class.
     *
     * @return class-string<Model>
     */
    protected function getModelClass(): string
    {
        return $this->modelClass;
    }

    // ###########################################################################
    //
    // Verify: instance
    //
    // ###########################################################################

    public function test_model_instance(): void
    {
        $instance = $this->getModel();

        $modelClass = $this->getModelClass();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$this->modelClass' => $this->modelClass,
        //     '$instance' => $instance,
        //     '$modelClass' => $modelClass,
        // ]);

        $this->assertInstanceOf($modelClass, $instance);
    }

    // ###########################################################################
    //
    // Verify: relationships
    //
    // ###########################################################################

    /**
     * Verify a model relationship.
     *
     * Returns an array of boolean results for the relationship types.
     */
    public function verifyRelationships(): void
    {
        if (! $this->hasRelationships) {
            // At least one test must be completed.
            $this->assertEmpty($this->belongsTo, 'Expecting belongsTo to be empty.');
            $this->assertEmpty($this->belongsToMany, 'Expecting belongsToMany to be empty.');
            $this->assertEmpty($this->hasMany, 'Expecting hasMany to be empty.');
            $this->assertEmpty($this->hasOne, 'Expecting hasOne to be empty.');

        }

        //        foreach ($this->belongsTo as $accessor => $meta) {
        //            $results['belongsTo'][$accessor] = $this->verifyRelationship(
        //                'belongsTo',
        //                $accessor,
        //                $meta
        //            );
        //        }
        //
        //        foreach ($this->belongsToMany as $accessor => $meta) {
        //            $results['belongsToMany'][$accessor] = $this->verifyRelationship(
        //                'belongsToMany',
        //                $accessor,
        //                $meta
        //            );
        //        }
        //
        foreach ($this->hasMany as $accessor => $meta) {
            $this->verifyRelationshipHasMany(
                $accessor,
                $meta
            );
        }

        foreach ($this->hasOne as $accessor => $meta) {
            $this->verifyRelationshipHasOne(
                $accessor,
                $meta
            );
        }
    }

    // ###########################################################################
    //
    // Test: relationships
    //
    // ###########################################################################

    /**
     * Test the model relationships.
     */
    public function test_verify_model_relationships(): void
    {
        $modelClass = $this->getModelClass();

        $this->verifyRelationships();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     'class' => get_called_class(),
        //     '$this->modelClass' => $this->modelClass,
        //     '$results' => $results,
        //     // '$this' => $this,
        // ]);
    }

    /**
     * Set test_factory_create_state to the method name on a factory state of the model.
     */
    protected ?string $test_factory_create_state = null;

    public function test_factory_create(): void
    {
        $modelClass = $this->getModelClass();
        $this->assertNotEmpty($modelClass);

        $meta = [];
        if ($this->test_factory_create_state !== null) {
            $meta['state'] = $this->test_factory_create_state;
        }

        $model = $this->getFactory($modelClass, $meta)->create();

        $this->assertInstanceOf($modelClass, $model);
    }
}

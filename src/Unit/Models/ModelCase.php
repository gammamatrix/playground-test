<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Log;
use Playground\Test\OrchestraTestCase;

/**
 * \Playground\Test\Unit\Models\ModelCase
 *
 * NOTE Set the model: protected string $modelClass = Model::class;
 */
abstract class ModelCase extends OrchestraTestCase
{
    /**
     * @var bool A model must be marked as not having relationships.
     *
     * @see testVerifyRelationships()
     */
    protected bool $hasRelationships = true;

    /**
     * @var array<int, string> Test belongsTo relationships.
     */
    protected array $belongsTo = [
        // 'item',
    ];

    /**
     * @var array<int, string> Test belongsToMany relationships.
     */
    protected array $belongsToMany = [
        // 'items',
    ];

    /**
     * @var array<int, string> Test hasMany relationships.
     */
    protected array $hasMany = [
        // 'items',
    ];

    /**
     * @var array<int, string> Test hasOne relationships.
     */
    protected array $hasOne = [
        // 'item',
    ];

    /**
     * @var array<int, string> Test morphToMany relationships.
     */
    protected array $morphToMany = [
        // 'items',
    ];

    /**
     * @var class-string<Model>
     */
    protected string $modelClass = Model::class;

    /**
     * @var array<string, mixed> The relationship types for testing.
     */
    protected array $relationshipTypes = [
        'belongsTo' => [],
        'belongsToMany' => [],
        'hasMany' => [],
        'hasOne' => [],
        'morphToMany' => [],
    ];

    protected function getModel(): Model
    {
        $modelClass = $this->getModelClass();

        return new $modelClass();
    }

    /**
     * Get the FQDN of the model class.
     *
     * @return class-string<Model> Returns the FQDN to the model class.
     */
    protected function getModelClass(): string
    {
        return $this->modelClass;
    }

    // Verify: instance

    public function test_model_instance(): void
    {
        $instance = $this->getModel();

        $modelClass = $this->getModelClass();

        $this->assertInstanceOf($modelClass, $instance);
    }

    // Verify: relationships

    /**
     * Verify a model relationship.
     */
    protected function verifyRelationship(string $relationshipType, string $accessor): bool
    {
        $hasRelationshipType = is_string($relationshipType)
            && isset($this->relationshipTypes[$relationshipType])
            && ! empty($this->{$relationshipType})
            && in_array($accessor, $this->{$relationshipType});
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     'class' => get_called_class(),
        //     '$this->modelClass' => $this->modelClass,
        //     '$hasRelationshipType' => $hasRelationshipType,
        // ]);

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$modelClass' => $this->getModelClass(),
        //     // '$model' => $this->getModel(),
        //     '$relationshipType' => $relationshipType,
        //     '$accessor' => $accessor,
        //     '$hasRelationshipType' => $hasRelationshipType,
        //     'belongsTo' => $this->belongsTo,
        //     'belongsToMany' => $this->belongsToMany,
        //     'relationshipTypes' => $this->relationshipTypes,
        //     'hasMany' => $this->hasMany,
        //     'hasOne' => $this->hasOne,
        // ]);

        if (! $hasRelationshipType) {
            $error = sprintf('Invalid relationship: %1$s', json_encode([
                '$modelClass' => $this->getModelClass(),
                '$relationshipType' => $relationshipType,
                '$accessor' => $accessor,
            ]));
            Log::error($error);

            // Unable to continue testing this model.
            return false;
        }

        /**
         * @var class-string<BelongsTo|BelongsToMany|HasMany|HasOne|MorphToMany>
         */
        $relationshipTypeClass = null;
        if ($relationshipType === 'belongsTo') {
            $relationshipTypeClass = BelongsTo::class;
        } elseif ($relationshipType === 'belongsToMany') {
            $relationshipTypeClass = BelongsToMany::class;
        } elseif ($relationshipType === 'hasMany') {
            $relationshipTypeClass = HasMany::class;
        } elseif ($relationshipType === 'hasOne') {
            $relationshipTypeClass = HasOne::class;
        } elseif ($relationshipType === 'morphToMany') {
            $relationshipTypeClass = MorphToMany::class;
        }

        $relationship = $this->getModel()->{$accessor}();
        $this->assertInstanceOf($relationshipTypeClass, $relationship);

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$modelClass' => $this->getModelClass(),
        //     // '$model' => $this->getModel(),
        //     '$relationship' => get_class($relationship),
        //     // '$relationship' => $relationship,
        //     // '$relationship' => get_class_methods($relationship),
        //     '$relationshipType' => $relationshipType,
        //     '$relationshipTypeClass' => $relationshipTypeClass,
        //     '$accessor' => $accessor,
        //     '$hasRelationshipType' => $hasRelationshipType,
        //     'belongsTo' => $this->belongsTo,
        //     'relationshipTypes' => $this->relationshipTypes,
        //     'belongsToMany' => $this->belongsToMany,
        //     'relationshipTypes' => $this->relationshipTypes,
        //     'hasMany' => $this->hasMany,
        // ]);
        return true;
    }

    /**
     * Verify a model relationship.
     *
     * @return array<string, mixed> Returns an array of boolean results for the relationship types.
     */
    protected function verifyRelationships(): array
    {
        $results = [
            'belongsTo' => [],
            'belongsToMany' => [],
            'hasMany' => [],
            'hasOne' => [],
            'morphToMany' => [],
        ];
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     'class' => get_called_class(),
        //     '$this->modelClass' => $this->modelClass,
        //     '$this->hasOne' => $this->hasOne,
        //     // '$this' => $this,
        // ]);

        if (! $this->hasRelationships) {
            // At least one test must be completed.
            $this->assertEmpty($this->belongsTo, 'Expecting belongsTo to be empty.');
            $this->assertEmpty($this->belongsToMany, 'Expecting belongsToMany to be empty.');
            $this->assertEmpty($this->hasMany, 'Expecting hasMany to be empty.');
            $this->assertEmpty($this->hasOne, 'Expecting hasOne to be empty.');
            $this->assertEmpty($this->morphToMany, 'Expecting morphToMany to be empty.');

            return $results;
        }

        foreach ($this->belongsTo as $accessor) {
            $results['belongsTo'][$accessor] = $this->verifyRelationship('belongsTo', $accessor);
        }

        foreach ($this->belongsToMany as $accessor) {
            $results['belongsToMany'][$accessor] = $this->verifyRelationship('belongsToMany', $accessor);
        }

        foreach ($this->hasMany as $accessor) {
            $results['hasMany'][$accessor] = $this->verifyRelationship('hasMany', $accessor);
        }

        foreach ($this->hasOne as $accessor) {
            $results['hasOne'][$accessor] = $this->verifyRelationship('hasOne', $accessor);
        }

        foreach ($this->morphToMany as $accessor) {
            $results['morphToMany'][$accessor] = $this->verifyRelationship('morphToMany', $accessor);
        }

        return $results;
    }

    // Test: relationships

    /**
     * Test the model relationships.
     */
    public function test_verify_model_relationships(): void
    {
        $modelClass = $this->getModelClass();

        $results = $this->verifyRelationships();
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

    public function test_model_factory_make(): void
    {
        $instance = null;

        $modelClass = $this->getModelClass();
        $this->assertNotEmpty($modelClass);

        if (is_callable([$modelClass, 'factory'])) {
            $instance = $modelClass::factory()->make();
        }

        $this->assertNotNull($instance);
        $this->assertInstanceOf($modelClass, $instance);
    }
}

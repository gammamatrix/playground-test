<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test\Unit\Models;

use GammaMatrix\Playground\Test\OrchestraTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Log;

/**
 * \GammaMatrix\Playground\Test\Unit\Models\ModelCase
 *
 * NOTE Set the model: protected string $modelClass = Model::class;
 */
abstract class ModelCase extends OrchestraTestCase
{
    /**
     * @var boolean $hasRelationships A model must be marked as not having relationships.
     * @see testVerifyRelationships()
     */
    protected bool $hasRelationships = true;

    /**
     * @var array $belongsTo Test belongsTo relationships.
     */
    protected array $belongsTo = [
        // 'item',
    ];

    /**
     * @var array $belongsToMany Test belongsToMany relationships.
     */
    protected array $belongsToMany = [
        // 'items',
    ];

    /**
     * @var array $hasMany Test hasMany relationships.
     */
    protected array $hasMany = [
        // 'items',
    ];

    /**
     * @var array $hasOne Test hasOne relationships.
     */
    protected array $hasOne = [
        // 'item',
    ];

    /**
     * @var array $morphToMany Test morphToMany relationships.
     */
    protected array $morphToMany = [
        // 'items',
    ];

    protected string $modelClass = Model::class;

    /**
     * @var array $relationshipTypes The relationship types for testing.
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
     */
    protected function getModelClass(): string
    {
        return $this->modelClass;
    }

    ############################################################################
    #
    # Verify: instance
    #
    ############################################################################

    public function test_model_instance(): void
    {
        $instance = $this->getModel();

        $modelClass = $this->getModelClass();

        $this->assertInstanceOf($modelClass, $instance);
    }

    ############################################################################
    #
    # Verify: relationships
    #
    ############################################################################

    /**
     * Verify a model relationship.
     *
     * @return boolean
     */
    protected function verifyRelationship(string $relationshipType, string $accessor): bool
    {
        $hasRelationshipType = is_string($relationshipType)
            && isset($this->relationshipTypes[$relationshipType])
            && !empty($this->{$relationshipType})
            && in_array($accessor, $this->{$relationshipType})
        ;
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

        if (!$hasRelationshipType) {
            $error = sprintf('Invalid relationship: %1$s', json_encode([
                '$modelClass' => $this->getModelClass(),
                '$relationshipType' => $relationshipType,
                '$accessor' => $accessor,
            ]));
            Log::error($error);

            // Unable to continue testing this model.
            return false;
        }

        $relationshipTypeClass = null;
        if ('belongsTo' === $relationshipType) {
            $relationshipTypeClass = BelongsTo::class;
        } elseif ('belongsToMany' === $relationshipType) {
            $relationshipTypeClass = BelongsToMany::class;
        } elseif ('hasMany' === $relationshipType) {
            $relationshipTypeClass = HasMany::class;
        } elseif ('hasOne' === $relationshipType) {
            $relationshipTypeClass = HasOne::class;
        } elseif ('morphToMany' === $relationshipType) {
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
     * @return array Returns an array of boolean results for the relationship types.
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

        if (!$this->hasRelationships) {
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

    ############################################################################
    #
    # Test: relationships
    #
    ############################################################################

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
}

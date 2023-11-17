<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test\Unit\Models;

use GammaMatrix\Playground\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use GammaMatrix\Playground\Test\TestCase;

/**
 * \GammaMatrix\Playground\Test\Unit\Models\ModelCase
 *
 */
abstract class ModelCase extends TestCase
{
    /**
     * @var string The model.
     * @see $hasController
     */
    const MODEL = '';

    /**
     * @var string The abstract model class.
     */
    const MODEL_ABSTRACT = Model::class;

    // /**
    //  * @var boolean $hasController
    //  */
    // protected $hasController = true;

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

    /**
     * @var string $modelClass AbstractUuidModel do not have controllers.
     */
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

    // /**
    // * @var boolean $verifyRelationshipModel Verify the relationship model has the expected models.
    // * @see testVerifyRelationships()
    // */
    // protected $verifyRelationshipModel = true;

    protected function getModel(): Model
    {
        $modelClass = $this->getModelClass();

        return new $modelClass;
    }

    /**
     * Get the FQDN of the model class.
     */
    protected function getModelClass(): string
    {
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$this' => $this,
        // ]);
        if (!empty(static::MODEL)) {
            $this->modelClass = static::MODEL;
            return $this->modelClass;
        }

        if (!empty($this->modelClass)
            && $this->modelClass !== Model::class
        ) {
            return $this->modelClass;
        }

        throw new \Exception(sprintf(
            'Expecting the static::MODEL class [%1$s], in the test, to exist: %2$s',
            $this->modelClass,
            get_called_class()
        ));

        // if ($this->hasController) {
        //     $modelClass = constant(sprintf('%1$s::MODEL', static::CONTROLLER));
        // }

        // $this->assertIsString($this->modelClass);
        // $this->assertNotEmpty($this->modelClass);
        // $this->assertTrue(class_exists($this->modelClass), sprintf(
        //     'Expecting the model class [%1$s], in the controller, to exist: %2$s',
        //     $modelClass,
        //     static::CONTROLLER
        // ));

        // $this->assertTrue($this->modelClass !== Model::class, sprintf(
        //     'Specify the model class with static::MODEL in the controller [%1$s] or in the test class: %2$s',
        //     static::CONTROLLER,
        //     get_called_class()
        // ));

        // // The model class is valid.
        // $this->modelClass = $modelClass;

        // return $this->modelClass;
    }

    ############################################################################
    #
    # Verify: instance
    #
    ############################################################################

    /**
     * Test the class instance.
     *
     * @return void
     */
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
        //     'static::MODEL_ABSTRACT' => static::MODEL_ABSTRACT,
        //     '$modelClass' => $modelClass,
        // ]);

        $this->assertInstanceOf($modelClass, $instance);

        $this->assertInstanceOf(static::MODEL_ABSTRACT, $instance);
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
        //     'static::CONTROLLER' => static::CONTROLLER,
        // ]);

        if (!$hasRelationshipType) {
            // if ($this->hasController) {
            //     $error = sprintf('Invalid relationship: %1$s', json_encode([
            //         'CONTROLLER' => static::CONTROLLER,
            //         '$relationshipType' => $relationshipType,
            //         '$accessor' => $accessor,
            //     ]));
            // } else {
                $error = sprintf('Invalid relationship: %1$s', json_encode([
                    'MODEL' => static::MODEL,
                    '$relationshipType' => $relationshipType,
                    '$accessor' => $accessor,
                ]));
            // }
            error_log($error);

            // Unable to continue testing.
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
        //     'static::CONTROLLER' => static::CONTROLLER,
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

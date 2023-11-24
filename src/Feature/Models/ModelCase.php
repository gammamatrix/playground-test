<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test\Feature\Models;

use GammaMatrix\Playground\Test\OrchestraTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;

/**
 * \GammaMatrix\Playground\Test\Feature\Models\ModelCase
 *
 * NOTE Set the model: protected string $modelClass = Model::class;
 */
abstract class ModelCase extends OrchestraTestCase
{
    use DatabaseTransactions;

    /**
     * @var boolean $hasRelationships A model must be marked as not having relationships.
     * @see testVerifyRelationships()
     */
    protected bool $hasRelationships = true;

    /**
     * @var array $belongsTo Test belongsTo relationships.
     */
    protected array $belongsTo = [
        // 'type' => ['use' => 'type', 'rule' => '_first', 'modelClass' => \Mods\Dam\Models\DamAssetType::class],
    ];

    /**
     * @var array $belongsToMany Test belongsToMany relationships.
     */
    protected array $belongsToMany = [
        // 'tags' => ['use' => 'factory', 'rule' => '_first', 'modelClass' => \Mods\Dam\Models\DamAssetTag::class],
    ];

    /**
     * @var array $hasMany Test hasMany relationships.
     */
    protected array $hasMany = [
        // 'lines' => ['key' => 'cart_id', 'modelClass' => \App\Models\Cartline::class],
    ];

    /**
     * @var array $hasOne Test hasOne relationships.
     */
    protected array $hasOne = [
        // 'coupon' => ['key' => 'coupon_id', 'rule' => 'create', 'modelClass' => \App\Coupon::class],
    ];

    protected string $modelClass = Model::class;

    /**
     * @var array $relationshipTypes The relationship types for testing.
     */
    protected $relationshipTypes = [
        'belongsTo' => [],
        'belongsToMany' => [],
        'hasMany' => [],
        'hasOne' => [],
    ];

    /**
    * @var boolean $verifyRelationshipModel Verify the relationship model has the expected models.
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
     * @return string Returns the FQDN to the model class.
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

    ############################################################################
    #
    # Verify: relationships
    #
    ############################################################################

    /**
     * Verify a model relationship.
     *
     */
    public function verifyRelationship(string $relationshipType, string $accessor): bool
    {
        $hasRelationshipType = is_string($relationshipType)
            && isset($this->relationshipTypes[$relationshipType])
            && !empty($this->{$relationshipType})
            && isset($this->{$relationshipType}[$accessor])
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
        }

        $relationship = $this->getModel()->{$accessor}();
        $this->assertInstanceOf($relationshipTypeClass, $relationship);

        $modelClass = $this->getModelClass();

        $model = $modelClass::factory()->create();

        if (!$this->verifyRelationshipModel) {
            // All done.
            return true;
        }

        // dd([
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
        //
        if ('belongsTo' === $relationshipType) {
        } elseif ('belongsToMany' === $relationshipType) {
        } elseif ('hasMany' === $relationshipType) {
            $this->verifyRelationshipHasMany($model, $accessor);
        } elseif ('hasOne' === $relationshipType) {
            $this->verifyRelationshipHasOne($model, $accessor);
        }

        return true;
    }

    /**
     * Verify a model relationship.
     *
     * @return array Returns an array of boolean results for the relationship types.
     */
    public function verifyRelationships()
    {
        $results = [
            'belongsTo' => [],
            'belongsToMany' => [],
            'hasMany' => [],
            'hasOne' => [],
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
            return $results;
        }

        foreach ($this->belongsTo as $accessor => $meta) {
            $results['belongsTo'][$accessor] = $this->verifyRelationship('belongsTo', $accessor);
        }

        foreach ($this->belongsToMany as $accessor => $meta) {
            $results['belongsToMany'][$accessor] = $this->verifyRelationship('belongsToMany', $accessor);
        }

        foreach ($this->hasMany as $accessor => $meta) {
            $results['hasMany'][$accessor] = $this->verifyRelationship('hasMany', $accessor);
        }

        foreach ($this->hasOne as $accessor => $meta) {
            $results['hasOne'][$accessor] = $this->verifyRelationship('hasOne', $accessor);
        }

        return $results;
    }

    /**
     * Verify a HasOne model relationship.
     *
     * @param Model $model
     * @param string $accessor
     *
     * @return array Returns an array of boolean results for the relationship types.
     */
    public function verifyRelationshipHasOne(Model $model, $accessor)
    {
        $hasAccessor = isset($this->hasOne[$accessor])
            && is_array($this->hasOne[$accessor])
            && !empty($this->hasOne[$accessor])
            && isset($this->hasOne[$accessor]['key'])
            && is_string($this->hasOne[$accessor]['key'])
            && !empty($this->hasOne[$accessor]['key'])
            // && isset($this->hasOne[$accessor]['use'])
            // && is_string($this->hasOne[$accessor]['use'])
        ;
        $hasModelClass = $hasAccessor
            && isset($this->hasOne[$accessor]['modelClass'])
            && is_string($this->hasOne[$accessor]['modelClass'])
            && !empty($this->hasOne[$accessor]['modelClass'])
            && class_exists($this->hasOne[$accessor]['modelClass'])
        ;

        $this->assertTrue($hasModelClass, sprintf(
            'Expecting the HasOne accessor [%1$s] to have a {use, key, modelClass} in %2$s::$hasOne[%1$s][modelClass]',
            $accessor,
            get_called_class()
        ));

        $rule = isset($this->hasOne[$accessor]['rule'])
            && is_string($this->hasOne[$accessor]['rule'])
            && !empty($this->hasOne[$accessor]['rule'])
            ? $this->hasOne[$accessor]['rule'] : ''
        ;
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$this->hasOne[$accessor]' => $this->hasOne[$accessor],
        // ]);

        $modelClass = $this->hasOne[$accessor]['modelClass'];

        if ('first' === $rule) {
            $m = $modelClass::first();
        } else {
            $m = $modelClass::factory()->create();
        }

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$m' => $m,
        // ]);
        $this->assertInstanceOf($this->hasOne[$accessor]['modelClass'], $m, sprintf(
            'Expecting the created HasOne model for the accessor [%1$s] to be an instance of %2$s - found: %3$s - %4$s',
            $accessor,
            $this->hasOne[$accessor]['modelClass'],
            $m ? get_class($m) : $m,
            get_called_class()
        ));

        $model->setAttribute($this->hasOne[$accessor]['key'], $m->id);

        if ($model->save()) {
            $model->refresh();
        }

        $this->assertSame(
            strval($model->getAttributeValue($this->hasOne[$accessor]['key'])),
            strval($m->id),
            sprintf(
                'Expecting the created HasOne model for the accessor [%1$s] to have m->id === model->%2$s - modelClass: %3$s - %4$s',
                $accessor,
                $this->hasOne[$accessor]['key'],
                $this->hasOne[$accessor]['modelClass'],
                get_class($m),
                get_called_class()
            )
        );

        $o = $model->{$accessor}()->first();

        $this->assertInstanceOf($this->hasOne[$accessor]['modelClass'], $o, sprintf(
            'Expecting the created HasOne model for the accessor [%1$s] to be an instance of %2$s - found: %3$s - %4$s',
            $accessor,
            $this->hasOne[$accessor]['modelClass'],
            $o ? get_class($o) : gettype($o),
            get_called_class()
        ));
        $this->assertSame(
            $o->id,
            $m->id,
            sprintf(
                'Expecting the created HasOne model for the accessor [%1$s] to have m->id === o->id - modelClass: %3$s - %4$s',
                $accessor,
                $this->hasOne[$accessor]['key'],
                $this->hasOne[$accessor]['modelClass'],
                get_class($o),
                get_called_class()
            )
        );
    }

    /**
     * Verify a HasMany model relationship.
     *
     * @param Model $model
     * @param string $accessor
     *
     * @return array Returns an array of boolean results for the relationship types.
     */
    public function verifyRelationshipHasMany(Model $model, $accessor)
    {
        $hasAccessor = isset($this->hasMany[$accessor])
            && is_array($this->hasMany[$accessor])
            && !empty($this->hasMany[$accessor])
            && isset($this->hasMany[$accessor]['key'])
            && is_string($this->hasMany[$accessor]['key'])
            && !empty($this->hasMany[$accessor]['key'])
            // && isset($this->hasMany[$accessor]['use'])
            // && is_string($this->hasMany[$accessor]['use'])
        ;
        $hasModelClass = $hasAccessor
            && isset($this->hasMany[$accessor]['modelClass'])
            && is_string($this->hasMany[$accessor]['modelClass'])
            && !empty($this->hasMany[$accessor]['modelClass'])
        ;

        $hasModelClassExists = $hasModelClass
            && class_exists($this->hasMany[$accessor]['modelClass'])
        ;

        $this->assertTrue($hasModelClassExists, sprintf(
            'Expecting the HasMany accessor [%1$s] to have {key, modelClass} in %2$s::$hasMany[%1$s] - %3$s',
            $accessor,
            $hasModelClass ? json_encode($this->hasMany[$accessor]) : 'misconfigured',
            get_called_class()
        ));

        $models = [];

        // Create 3 models.
        $modelClass = $this->hasMany[$accessor]['modelClass'];
        $models[] = $modelClass::factory()->create([
            $this->hasMany[$accessor]['key'] => $model->id,
        ]);

        $models[] = $modelClass::factory()->create([
            $this->hasMany[$accessor]['key'] => $model->id,
        ]);

        $models[] = $modelClass::factory()->create([
            $this->hasMany[$accessor]['key'] => $model->id,
        ]);

        foreach ($model->{$accessor}()->get() as $m) {
            $this->assertInstanceOf($this->hasMany[$accessor]['modelClass'], $m, sprintf(
                'Expecting the created HasMany model for the accessor [%1$s] to be an instance of %2$s - found: %3$s - %4$s',
                $accessor,
                $this->hasMany[$accessor]['modelClass'],
                get_class($m),
                get_called_class()
            ));
            $this->assertSame(
                $m->getAttributeValue($this->hasMany[$accessor]['key']),
                $model->id,
                sprintf(
                    'Expecting the created HasMany model for the accessor [%1$s] to have model->id === m->%2$s - modelClass: %3$s - %4$s',
                    $accessor,
                    $this->hasMany[$accessor]['key'],
                    $this->hasMany[$accessor]['modelClass'],
                    get_class($m),
                    get_called_class()
                )
            );
            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '__FILE__' => __FILE__,
            //     '__LINE__' => __LINE__,
            //     '$m' => $m->toArray(),
            // ]);
        }
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$modelClass' => $this->getModelClass(),
        //     '$model class' => get_class($model),
        //     '$model' => $model->toArray(),
        //     '$models[0]' => $models[0]->toArray(),
        //     '$models[1]' => $models[1]->toArray(),
        //     '$models[2]' => $models[2]->toArray(),
        //     // '$hasAccessor' => $hasAccessor,
        //     // '$hasModelClass' => $hasModelClass,
        //     'hasMany' => $this->hasMany,
        // ]);
    }

    ############################################################################
    #
    # Test: relationships
    #
    ############################################################################

    /**
     * Test the model relationships.
     *
     * @return void
     */
    public function testVerifyRelationships()
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

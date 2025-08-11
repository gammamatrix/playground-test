<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Testing Model Lines
    |--------------------------------------------------------------------------
    |
    |
    */

    'factory.state.invalid' => 'The state does not exist for the model: :model::factory()->:state($options)->make()',
    'factory.404' => 'Unable to create a model from a factory: :model::factory()->make()',

    'accessor.404' => 'Expecting the model to have the accessor: :model->:accessor()',

    'debug.feature.has.one' => 'Testing HasOne :model:::accessor()',
    'debug.feature.has.one.success' => 'Testing HasOne :model:::accessor() was successful',
];

<?php

use Chrisabey84\LaravelCachedOptionsList\HasCachedOptionsList;
use Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Models\TestModel;
use Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Models\TestModelWithoutTrait;
use function Pest\Laravel\artisan;
use Symfony\Component\Console\Exception\RuntimeException;

it('will error if model name is not passed', function () {
    artisan('cached-options:clear');
})->throws(RuntimeException::class);

it('will error if model cannot be found', function () {
    artisan('cached-options:clear', [
        'model' => 'SomeNonExistentModel',
    ])
        ->expectsOutput("Model 'SomeNonExistentModel' does not exist. Please ensure you have included the fully qualified namespace.")
        ->assertFailed();
});

it('will error if model is not using trait', function () {
    $modelClass = TestModelWithoutTrait::class;
    $traitClass = HasCachedOptionsList::class;

    artisan('cached-options:clear', [
        'model' => $modelClass,
    ])
        ->expectsOutput("Model '{$modelClass}' is not using the '{$traitClass}' trait.")
        ->assertFailed();
});

it('will clear options cache for model', function () {
    $modelClass = TestModel::class;

    artisan('cached-options:clear', [
        'model' => $modelClass,
    ])
        ->expectsOutput("Options cache for model '{$modelClass}' cleared successfully.")
        ->assertSuccessful();
});

<?php

use Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Models\TestModel;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->initialEvent = Event::getFacadeRoot();
    Event::fake();

    TestModel::factory()
        ->count(2)
        ->state(new Sequence(
            ['name' => 'Option One'],
            ['name' => 'Option Two'],
        ))
        ->create();
});

it('can get an array of key value pairs', function () {
    $list = TestModel::asSelectArray();

    expect($list)->toBeArray()
        ->and($list)->toEqual(
            [
                1 => 'Option One',
                2 => 'Option Two',
            ]
        );
});

it('will query the database if cache does not exist', function () {
    expect(Cache::has('test-model-cached-options-list'))->toBeFalse();

    TestModel::asSelectArray();

    assertCacheMissed();
});

it('will retrieve list from cache if key exists', function () {
    $cachedData = setCache();

    assertCacheHit($cachedData, TestModel::asSelectArray());
});

it('will clear cache when model is created', function () {
    setCache();

    //reset event dispatcher so model events will fire
    TestModel::setEventDispatcher(test()->initialEvent);

    TestModel::factory()
        ->create(['name' => 'Option Three']);

    //fake the event facade, so we can assert the cache events
    Event::fake();

    expect(TestModel::asSelectArray())
        ->toEqual([
            1 => 'Option One',
            2 => 'Option Two',
            3 => 'Option Three',
        ]);

    assertCacheMissed();
});

it('will clear cache when model is updated', function () {
    setCache();

    //reset event dispatcher so model events will fire
    TestModel::setEventDispatcher(test()->initialEvent);

    TestModel::first()
        ->update(['name' => 'Updated Option']);

    //fake the event facade, so we can assert the cache events
    Event::fake();

    expect(TestModel::asSelectArray())
        ->toEqual([
            1 => 'Updated Option',
            2 => 'Option Two',
        ]);

    assertCacheMissed();
});

it('will clear cache when model is soft deleted', function () {
    setCache();

    //reset event dispatcher so model events will fire
    TestModel::setEventDispatcher(test()->initialEvent);

    $model = TestModel::first();

    $model->delete();

    assertSoftDeleted(TestModel::class, [
        'id' => $model->id,
    ]);

    //fake the event facade, so we can assert the cache events
    Event::fake();

    expect(TestModel::asSelectArray())
        ->toEqual([
            2 => 'Option Two',
        ]);

    assertCacheMissed();
});


it('will clear cache when model is force deleted', function () {
    setCache();

    //reset event dispatcher so model events will fire
    TestModel::setEventDispatcher(test()->initialEvent);

    $model = TestModel::first();

    $model->forceDelete();

    assertDatabaseMissing(TestModel::class, [
        'id' => $model->id,
    ]);

    //fake the event facade, so we can assert the cache events
    Event::fake();

    expect(TestModel::asSelectArray())
        ->toEqual([
            2 => 'Option Two',
        ]);

    assertCacheMissed();
});


/************ HELPER FUNCTIONS ****************/

function assertCacheHit(array $expected, array $actual): void
{
    Event::assertDispatched(function (CacheHit $event) {
        return $event->key === 'test-model-cached-options-list';
    });

    expect($actual)->toBeArray()
        ->and($actual)->toEqual($expected);
}

function assertCacheMissed(): void
{
    Event::assertDispatched(function (CacheMissed $event) {
        return $event->key === 'test-model-cached-options-list';
    });
}

function setCache(): array
{
    $cachedData = [
        1 => 'Option One',
        2 => 'Option Two',
    ];

    Cache::add('test-model-cached-options-list',  $cachedData);

    return $cachedData;
}

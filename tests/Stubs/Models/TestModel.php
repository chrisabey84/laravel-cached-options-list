<?php

namespace Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Models;

use Chrisabey84\LaravelCachedOptionsList\HasCachedOptionsList;
use Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Factories\TestModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasCachedOptionsList;

    protected $table = 'test-models';

    protected $fillable = ['name'];

    protected static function newFactory(): TestModelFactory
    {
        return TestModelFactory::new();
    }
}

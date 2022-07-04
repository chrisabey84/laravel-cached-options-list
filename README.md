
# Cache all the rows in a relationship model as a select array for  use in forms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/chrisabey84/laravel-cached-options-list.svg?style=flat-square)](https://packagist.org/packages/chrisabey84/laravel-cached-options-list)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/chrisabey84/laravel-cached-options-list/run-tests?label=tests)](https://github.com/chrisabey84/laravel-cached-options-list/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/chrisabey84/laravel-cached-options-list/Check%20&%20fix%20styling?label=code%20style)](https://github.com/chrisabey84/laravel-cached-options-list/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/chrisabey84/laravel-cached-options-list.svg?style=flat-square)](https://packagist.org/packages/chrisabey84/laravel-cached-options-list)

A simple package that allows you to cache all the rows in a relationship model for use in select arrays in forms.

## Installation

You can install the package via composer:

```bash
composer require chrisabey84/laravel-cached-options-list
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Chrisabey84\LaravelCachedOptionsList\LaravelCachedOptionsListServiceProvider
```

This is the contents of the published config file:

```php
return [
    'key' => 'id', //The index/key column for the select options array
    'value' => 'name', //The value column for the select options array
];
```

## Usage

Adding the `HasCachedOptionsList` trait to any of your models will provide the following functionality:

```php
$rows = \App\Models\MyModel::asSelectArray();
```

Will retrieve all rows as an associative array which can then be used in your blade templates to populate the option in a select box:

```php
<select name="mySelectBox">
@foreach($rows as $key => $value)
	<option value="{{ $key }}">{{ $value }}</option>
@endforeach
</select>
```

### Custom Behavior

By default `asSelectArray()` will retrieve all rows from the database table however, you can customise this behavior by overriding the following method in your model which must return a `Builder` instance:

```php
protected static function buildQuery(): Builder
{
	return static::query();
}
```

### Clearing The Cache

The cache will be automatically cleared when creating, updating or deleting your models.

To manually clear the cache, you can either call:

```php
\App\Models\MyModel::clearOptionsCache();
```

Or use the handy artisan command with the model name as the argument:

```bash
php artisan cached-options:clear '\App\Models\MyModel'
```

## Testing

```bash
composer test
```


## Credits

- [Christopher Abey](https://github.com/chrisabey84)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

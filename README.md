# Laravel Magic Scopes




[![Latest Version on Packagist](https://img.shields.io/packagist/v/safemood/laravel-magic-scopes.svg?style=flat-square)](https://packagist.org/packages/safemood/laravel-magic-scopes)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/safemood/laravel-magic-scopes/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/safemood/laravel-magic-scopes/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/safemood/laravel-magic-scopes/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/safemood/laravel-magic-scopes/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/safemood/laravel-magic-scopes.svg?style=flat-square)](https://packagist.org/packages/safemood/laravel-magic-scopes)

Laravel Magic Scopes auto-generates  query scopes for your models — no code needed, just magic 🔮.

---

## Table of Contents

- [Laravel Magic Scopes](#laravel-magic-scopes)
  - [Table of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Usage](#usage)
    - [What It can do](#what-it-can-do)
    - [Boolean Field Scopes](#boolean-field-scopes)
    - [Enum Field Scopes](#enum-field-scopes)
    - [Foreign Key Scopes](#foreign-key-scopes)
    - [JSON Field Scopes](#json-field-scopes)
    - [Number Field Scopes](#number-field-scopes)
    - [Date Field Scopes](#date-field-scopes)
  - [Extend](#extend)
  - [Testing](#testing)
  - [Changelog](#changelog)
  - [Contributing](#contributing)
  - [Security Vulnerabilities](#security-vulnerabilities)
  - [Credits](#credits)
  - [License](#license)


---

## Installation

You can install the package via composer:

```bash
composer require safemood/laravel-magic-scopes
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="magic-scopes-config"
```

This is the contents of the published config file:

```php
<?php

declare(strict_types=1);

return [
 
    'resolvers' => [
        \Safemood\MagicScopes\Resolvers\BooleanFieldScopeResolver::class,
        \Safemood\MagicScopes\Resolvers\DateFieldScopeResolver::class,
        \Safemood\MagicScopes\Resolvers\EnumFieldScopeResolver::class,
        \Safemood\MagicScopes\Resolvers\ForeignKeyFieldScopeResolver::class,
        \Safemood\MagicScopes\Resolvers\JsonFieldScopeResolver::class,
        \Safemood\MagicScopes\Resolvers\NumberFieldScopeResolver::class,
        \Safemood\MagicScopes\Resolvers\StringFieldScopeResolver::class,
    ],
];

```

## Usage

Simply use the HasMagicScopes trait in your Eloquent model:

```php
use Safemood\MagicScopes\Traits\HasMagicScopes;

class Post extends Model
{
    use HasMagicScopes;

}

```
---
### What It can do

```php
$posts = Post::where('views', '>', 216) // query
    ->notPublished()                    // magic scope
    ->recent()                         // real scope
    ->createdAfter('2025-05-01')      // magic scope
    ->get();
```
---

### Boolean Field Scopes

```php
Post::published()->get();             // where('is_published', true)
Post::notPublished()->get();          // where('is_published', false)

Post::hasFeaturedImage()->get();      // where('has_featured_image', true)
Post::hasNotFeaturedImage()->get();   // where('has_featured_image', false)

Post::sticky()->get();                // where('is_sticky', true)
Post::notSticky()->get();             // where('is_sticky', false)

```

---

### Enum Field Scopes

```php
Post::statusIs('published')->get();       // where('status', 'published')
Post::typeIs('announcement')->get();      // where('type', 'announcement')
```
---

### Foreign Key Scopes

```php
Post::forUser(1)->get();                  // where('user_id', 1)
Post::forCategory([1, 2])->get();         // whereIn('category_id', [1, 2])

Post::withUser()->get();                 // with('user')
Post::withUser([1, 2])->get();           // with(['user' => fn ($q) => $q->whereIn('id', [1, 2])])

Post::withAuthor(10)->get();             // with(['author' => fn ($q) => $q->where('id', 10)])
```
---

### JSON Field Scopes

```php
Post::rentRequestsContains('rooms_count', 2)->get();   // whereJsonContains('rent_requests->rooms_count', 2)
Post::rentContains('city', 'Tunis')->get();            // whereJsonContains('rent->city', 'Tunis')
Post::settingsContains('timezone', 'UTC')->get();      // whereJsonContains('settings->timezone', 'UTC')
```
---

### Number Field Scopes

```php
Post::whereViewsGreaterThan(50)->get();     // where('views', '>', 50)
Post::whereScoreEquals(90)->get();          // where('score', '=', 90)
Post::wherePriceBetween(100, 200)->get();   // whereBetween('price', [100, 200])
Post::whereDownloadsEquals(10)->get();      // where('downloads', '=', 10)
```
---

### Date Field Scopes

```php
Post::reviewedAt('2024-05-10')->get(); // Equivalent to: whereDate('reviewed_at', '2024-05-10')

Post::reviewedBefore('2024-05-15')->get(); // Equivalent to: whereDate('reviewed_at', '<', '2024-05-15')

Post::reviewedAfter('2024-05-15')->get(); // Equivalent to: whereDate('reviewed_at', '>', '2024-05-15')

Post::reviewedBetween('2024-05-10', '2024-05-20')->get(); // Equivalent to: whereBetween('reviewed_at', ['2024-05-10', '2024-05-20'])
```


## Extend

You can create your own custom scope resolver if you want to control how scopes are resolved from method names.

Here's how to create a custom ScopeResolver:

⚠️ **Important:**  
Custom resolvers should not match the same method as another resolver. 
If multiple resolvers match, an exception is thrown to avoid conflicts.


```php
<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class CustomScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, Builder $builder): bool
    {
        return Str::startWith($method, 'customPrefix');
    }

   public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        $column = 'your_column_name'; // extract from method name
        $value = $parameters[0] ?? null; // get the value, depending on your logic

        return $builder->where($column, $value); // Apply basic WHERE condition
    }
}
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Khalil Bouzidi](https://github.com/Safemood)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

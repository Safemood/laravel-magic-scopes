<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Scope Resolvers
    |--------------------------------------------------------------------------
    |
    | Define the list of default scope resolvers. You can add or remove resolvers
    | here as needed.
    |
    */

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

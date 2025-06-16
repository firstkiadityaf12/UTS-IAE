<?php declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Controls the HTTP route that your GraphQL server responds to.
    | You may set `route` => false, to disable the default route
    | registration and take full control.
    |
    */
    'route' => [
        /*
         * The URI the endpoint responds to, e.g. mydomain.com/graphql.
         */
        'uri' => '/graphql',

        /*
         * Lighthouse creates a named route for convenient URL generation and redirects.
         */
        'name' => 'graphql',

        /*
         * Beware that middleware defined here runs before the GraphQL execution phase,
         * make sure to return spec-compliant responses in case an error is thrown.
         */
        'middleware' => [
            Nuwave\Lighthouse\Http\Middleware\AcceptJson::class,
            Nuwave\Lighthouse\Http\Middleware\AttemptAuthentication::class,
        ],

        /*
         * The `prefix`, `domain` and `where` configuration options are optional.
         */
        // 'prefix' => '',
        // 'domain' => '',
        // 'where' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | The guards to use for authenticating GraphQL requests, if needed.
    | Used in directives such as `@guard` or the `AttemptAuthentication` middleware.
    | Falls back to the Laravel default if `null`.
    |
    */
    'guards' => null,

    /*
    |--------------------------------------------------------------------------
    | Schema Path
    |--------------------------------------------------------------------------
    |
    | Path to your .graphql schema file.
    | Additional schema files may be imported from within that file.
    |
    */
    'schema_path' => base_path('graphql/schema.graphql'),

    /*
    |--------------------------------------------------------------------------
    | Schema Cache
    |--------------------------------------------------------------------------
    |
    | A large part of schema generation consists of parsing and AST manipulation.
    | This operation is very expensive, so it is highly recommended enabling
    | caching of the final schema to optimize performance of large schemas.
    |
    */
    'schema_cache' => [
        'enable' => env('LIGHTHOUSE_SCHEMA_CACHE_ENABLE', env('APP_ENV') !== 'local'),
        'path' => env('LIGHTHOUSE_SCHEMA_CACHE_PATH', base_path('bootstrap/cache/lighthouse-schema.php')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Directive Tags
    |--------------------------------------------------------------------------
    |
    | Should the `@cache` directive use a tagged cache?
    |
    */
    'cache_directive_tags' => false,

    /*
    |--------------------------------------------------------------------------
    | Query Cache
    |--------------------------------------------------------------------------
    |
    | Caches the result of parsing incoming query strings to boost performance on subsequent requests.
    |
    */
    'scalars' => [
        'DateTime' => \Nuwave\Lighthouse\Schema\Types\Scalars\DateTime::class,
    ],

    'query_cache' => [
        'enable' => env('LIGHTHOUSE_QUERY_CACHE_ENABLE', true),
        'store' => env('LIGHTHOUSE_QUERY_CACHE_STORE', null),
        'ttl' => env('LIGHTHOUSE_QUERY_CACHE_TTL', 24 * 60 * 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Cache
    |--------------------------------------------------------------------------
    |
    | Caches the result of validating queries to boost performance on subsequent requests.
    |
    */
    'validation_cache' => [
        'enable' => env('LIGHTHOUSE_VALIDATION_CACHE_ENABLE', false),
        'store' => env('LIGHTHOUSE_VALIDATION_CACHE_STORE', null),
        'ttl' => env('LIGHTHOUSE_VALIDATION_CACHE_TTL', 24 * 60 * 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Parse source location
    |--------------------------------------------------------------------------
    |
    | Should the source location be included in the AST nodes resulting from query parsing?
    | Setting this to `false` improves performance, but omits the key `locations` from errors.
    |
    */
    'parse_source_location' => true,

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    | These are the default namespaces where Lighthouse looks for classes to
    | extend functionality of the schema. You may pass in either a string
    | or an array, they are tried in order and the first match is used.
    |
    */
    'namespaces' => [
        'models' => ['App\\Models', 'App'],
        'queries' => 'App\\GraphQL\\Queries',
        'mutations' => 'App\\GraphQL\\Mutations',
        'subscriptions' => 'App\\GraphQL\\Subscriptions',
        'types' => 'App\\GraphQL\\Types',
        'interfaces' => 'App\\GraphQL\\Interfaces',
        'unions' => 'App\\GraphQL\\Unions',
        'scalars' => 'App\\GraphQL\\Scalars',
        'directives' => ['App\\GraphQL\\Directives'],
        'validators' => 'App\\GraphQL\\Validators',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | Control how Lighthouse handles security related query validation.
    | Read more at https://webonyx.github.io/graphql-php/security/
    |
    */
    'security' => [
        'max_query_complexity' => GraphQL\Validator\Rules\QueryComplexity::DISABLED,
        'max_query_depth' => GraphQL\Validator\Rules\QueryDepth::DISABLED,
        'disable_introspection' => (bool) env('LIGHTHOUSE_SECURITY_DISABLE_INTROSPECTION', false)
            ? GraphQL\Validator\Rules\DisableIntrospection::ENABLED
            : GraphQL\Validator\Rules\DisableIntrospection::DISABLED,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Set defaults for the pagination features within Lighthouse, such as
    | the @paginate directive, or paginated relation directives.
    |
    */
    'pagination' => [
        'default_count' => 10,
        'max_count' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Control the debug level as described in https://webonyx.github.io/graphql-php/error-handling/
    | Debugging is only applied if the global Laravel debug config is set to true.
    |
    */
    'debug' => env('LIGHTHOUSE_DEBUG', GraphQL\Error\DebugFlag::INCLUDE_DEBUG_MESSAGE | GraphQL\Error\DebugFlag::INCLUDE_TRACE),

    /*
    |--------------------------------------------------------------------------
    | Error Handlers
    |--------------------------------------------------------------------------
    |
    | Register error handlers that receive the Errors that occur during execution
    | and handle them. You may use this to log, filter or format the errors.
    | The classes must implement \Nuwave\Lighthouse\Execution\ErrorHandler
    |
    */
    'error_handlers' => [
        Nuwave\Lighthouse\Execution\AuthenticationErrorHandler::class,
        Nuwave\Lighthouse\Execution\AuthorizationErrorHandler::class,
        Nuwave\Lighthouse\Execution\ValidationErrorHandler::class,
        Nuwave\Lighthouse\Execution\ReportingErrorHandler::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Middleware
    |--------------------------------------------------------------------------
    |
    | Register global field middleware directives that wrap around every field.
    | Execution happens in the defined order, before other field middleware.
    | The classes must implement \Nuwave\Lighthouse\Support\Contracts\FieldMiddleware
    |
    */
    'field_middleware' => [
        Nuwave\Lighthouse\Schema\Directives\TrimDirective::class,
        Nuwave\Lighthouse\Schema\Directives\ConvertEmptyStringsToNullDirective::class,
        Nuwave\Lighthouse\Schema\Directives\SanitizeDirective::class,
        Nuwave\Lighthouse\Validation\ValidateDirective::class,
        Nuwave\Lighthouse\Schema\Directives\TransformArgsDirective::class,
        Nuwave\Lighthouse\Schema\Directives\SpreadDirective::class,
        Nuwave\Lighthouse\Schema\Directives\RenameArgsDirective::class,
        Nuwave\Lighthouse\Schema\Directives\DropArgsDirective::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Global ID
    |--------------------------------------------------------------------------
    |
    | The name that is used for the global id field on the Node interface.
    | When creating a Relay compliant server, this must be named "id".
    |
    */
    'global_id_field' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Persisted Queries
    |--------------------------------------------------------------------------
    |
    | Lighthouse supports Automatic Persisted Queries (APQ), compatible with the
    | Apollo implementation.
    |
    */
    'persisted_queries' => true,

    /*
    |--------------------------------------------------------------------------
    | Transactional Mutations
    |--------------------------------------------------------------------------
    |
    | If set to true, the execution of built-in directives that mutate models
    | will be wrapped in a transaction to ensure atomicity.
    |
    */
    'transactional_mutations' => true,

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment Protection
    |
    | If set to true, mutations will use forceFill() over fill() when populating
    | a model with arguments in mutation directives.
    |
    */
    'force_fill' => true,

    /*
    | Batchload Relations
    |
    | If set to true, relations marked with directives like @hasMany or @belongsTo
    | will be optimized by combining the queries through the BatchLoader.
    |
    */
    'batchload_relations' => true,

    /*
    | Shortcut Foreign Key Selection
    |
    | If set to true, Lighthouse will shortcut queries where the client selects only the
    | foreign key pointing to a related model.
    |
    */
    'shortcut_foreign_key_selection' => false,

    /*
    | GraphQL Subscriptions
    |
    | Here you can define GraphQL subscription broadcaster and storage drivers
    | as well their required configuration options.
    |
    */
    'subscriptions' => [
        'queue_broadcasts' => env('LIGHTHOUSE_QUEUE_BROADCASTS', true),
        'broadcasts_queue_name' => env('LIGHTHOUSE_BROADCASTS_QUEUE_NAME', null),
        'storage' => env('LIGHTHOUSE_SUBSCRIPTION_STORAGE', 'redis'),
        'storage_ttl' => env('LIGHTHOUSE_SUBSCRIPTION_STORAGE_TTL', null),
        'encrypted_channels' => env('LIGHTHOUSE_SUBSCRIPTION_ENCRYPTED', false),
        'broadcaster' => env('LIGHTHOUSE_BROADCASTER', 'pusher'),
        'broadcasters' => [
            'log' => [
                'driver' => 'log',
            ],
            'echo' => [
                'driver' => 'echo',
                'connection' => env('LIGHTHOUSE_SUBSCRIPTION_REDIS_CONNECTION', 'default'),
                'routes' => Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class . '@echoRoutes',
            ],
            'pusher' => [
                'driver' => 'pusher',
                'connection' => 'pusher',
                'routes' => Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class . '@pusher',
            ],
            'reverb' => [
                'driver' => 'pusher',
                'connection' => 'reverb',
                'routes' => Nuwave\Lighthouse\Subscriptions\SubscriptionRouter::class . '@reverb',
            ],
        ],
        'exclude_empty' => env('LIGHTHOUSE_SUBSCRIPTION_EXCLUDE_EMPTY', true),
    ],

    /*
    | Defer
    |
    | Configuration for the experimental @defer directive support.
    |
    */
    'defer' => [
        'max_nested_fields' => 0,
        'max_execution_ms' => 0,
    ],

    /*
    | Apollo Federation
    |
    | Lighthouse can act as a federated service.
    |
    */
    'federation' => [
        'entities_resolver_namespace' => 'App\\GraphQL\\Entities',
    ],

    /*
    | Tracing
    |
    | Configuration for tracing support.
    |
    */
    'tracing' => [
        'driver' => Nuwave\Lighthouse\Tracing\ApolloTracing\ApolloTracing::class,
    ],
];

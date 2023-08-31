<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Rabsana-trade will be accessible from. If the
    | setting is null, Rabsana-trade will reside under the same domain as the
    | application. Otherwise, this value will be used as the subdomain.
    |
    */

    'domain' => env('RABSANA_TRADE_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Rabsana-trade will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('RABSANA_TRADE_PATH', 'rabsana-trade'),

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade Admin Api middleware
    |--------------------------------------------------------------------------
    |
    | Here you can add the middlewares for public and private routes.
    | for example you can set the auth:api middleware to private routes to check
    | the user is authenticated or not
    */

    'adminApiMiddlewares' => [
        'group'  => 'web', // web or api
        'public' => [],
        'private' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade  Api middleware
    |--------------------------------------------------------------------------
    |
    | Here you can add the middlewares for public and private routes.
    | for example you can set the auth:api middleware to private routes to check
    | the user is authenticated or not
    */

    'apiMiddlewares' => [
        'group' => 'api',  // web or api
        'public' => [],
        'private' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade  views config
    |--------------------------------------------------------------------------
    |
    | for showing the views you can determine these configs or you can publish
    | the package views
    |
    */

    'views' => [
        'admin' => [
            'extends'           => 'rabsana-trade::admin.master',
            'content-section'   => 'content',
            'title-section'     => 'title',
            'scripts-stack'     => 'scripts',
            'styles-stack'      => 'styles'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade  events
    |--------------------------------------------------------------------------
    | You can set your listener for each event
    |
    |
    */

    'ordersHaveFilledEvent' => [],

    'orderCreatedEvent'     => [],

    'orderCancelledEvent'   => [],

    'tradeEvent'   => [],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade  pusher
    |--------------------------------------------------------------------------
    | for streaming data you can set pusher
    |
    |
    */
    'pusher_is_active'      => true,
    'pusher_app_id'         => '1279915',
    'pusher_app_key'        => '17f373c8a4398cdd43a2',
    'pusher_app_secret'     => '87063bdb8c7b33390218',
    'pusher_options'        => [
        'cluster' => 'eu',
        'useTLS' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade  validations
    |--------------------------------------------------------------------------
    | You can set your custom validation for the endpoints
    |
    |
    */

    'storeOrderApiRequest' => [
        'symbol'            => [],
        'side'              => [],
        'type'              => [],
        'base_qty'          => [],
        'price'             => [],
        'user_description'  => [],
    ],

    'storeSymbolAdminApiRequest' => [
        'base'              => [],
        'quote'             => []
    ],

    'updateSymbolAdminApiRequest' => [
        'base'              => [],
        'quote'             => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-trade  tasks
    |--------------------------------------------------------------------------
    | you can provide some data for the some parts of package
    | 
    |
    |
    */

    /**
     * Get Symbol average price with task
     *
     * @method run()
     * @param  string  $base
     * @param  string  $quote
     * @param  string  $side
     * @param  int  $minute
     * @return float
     */
    'getSymbolAveragePriceFrom' => '',


];

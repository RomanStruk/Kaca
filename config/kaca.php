<?php

use Kaca\Http\Middleware\Authorize;

return [
    /**
     * This is the subdomain where Kaca will be accessible from. If the
     * setting is null, Kaca will reside under the same domain as the
     * application. Otherwise, this value will be used as the subdomain.
    */
    'domain' => env('KACA_DOMAIN'),

    /**
     * This is the URI path where Kaca will be accessible from. Feel free
     * to change this path to anything you like. Note that the URI will not
     * affect the paths of its internal API that aren't exposed to users.
    */
    'prefix' => env('KACA_PREFIX'),

    /**
     * These middlewares will be assigned to every Checkbox route, giving you
     * the chance to add your own middleware to this list or change any of
     * the existing middleware. Or, you can simply stick with this list.
    */
    'middleware' => [
        'web',
        'auth',
        Authorize::class,
    ],

    /**
     * Base url for api checkbox.ua
     */
    'base_url' => 'https://api.checkbox.in.ua',

    /**
     * Date format for views
     */
    'date_format' => 'd.m.y H:i',

    /**
     * Default queue for jobs
     */
    'queue' => env('KACA_QUEUE','default'),

    /**
     * This configuration value informs Kaca which "stack" you will be
    * using for your application. In general, this value is set for you
    * during installation and will not need to be changed after that.
    */
    'stack' => 'tailwind',
];

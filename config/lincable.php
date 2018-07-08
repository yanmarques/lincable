<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud
    |--------------------------------------------------------------------------
    |
    | Here you register the default cloud driver used store the files. By default
    | if this configuration is not present, we will use the default filesystem
    | driver, which will read from flysystem configuration. 
    |
    */

    'disk' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Root Path
    |--------------------------------------------------------------------------
    |
    | This value is the name of the root directory for all paths. This is used
    | when searching for a path that retrieves the directory with root.
    |
    */
    
    'root' => env('LINCABLE_ROOT'),

    /*
    |--------------------------------------------------------------------------
    | Models Configuration
    |--------------------------------------------------------------------------
    |
    | Here you configure the model basic configuration. This should be changed 
    | in case you will not use this, otherwise will help you on the url conf
    | with namespace shorthand and the field on model to save the link. 
    |
    */

    'models' => [
        'namespace' => 'App',
        'url_field' => 'preview'
    ],

    /*
    |--------------------------------------------------------------------------
    | URL Conf
    |--------------------------------------------------------------------------
    |
    | Here you register how the factory will build the url when storing files on
    | the model. The link generation accepts dynamic parameters that will be loaded  
    | for the given model instance. 
    | 
    */
    
    'urls' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Parsers
    |--------------------------------------------------------------------------
    |
    | Here you register your custom parsers for compiling the url. The parsers can 
    | match a given pattern on url path parameter, and execute some logic to return
    | some expected behavior. 
    | 
    */

    'parsers' => [
        // 
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Parsers
    |--------------------------------------------------------------------------
    |
    | Here will be the default parsers loaded from media manager. The default parser
    | is the colon parser with the already provided formatters. This is the default
    | configuration for running lincable, althought you are able to change this
    | to fit your requirements.
    | 
    */

    'default_parsers' => [
        \Lincable\Parsers\ColonParser::class => [
            \Lincable\Formatters\YearFormatter::class,
            \Lincable\Formatters\DayFormatter::class,
            \Lincable\Formatters\MonthFormatter::class,
            \Lincable\Formatters\RandomFormatter::class,
            \Lincable\Formatters\TimestampsFormatter::class
        ]
    ]
];
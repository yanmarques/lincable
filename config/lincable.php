<?php

use Lincable\Parsers\ColonParser;
use Lincable\Formatters\DayFormatter;
use Lincable\Formatters\YearFormatter;
use Lincable\Formatters\MonthFormatter;
use Lincable\Formatters\RandomFormatter;
use Lincable\Formatters\TimestampsFormatter;

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
    | Parsers
    |--------------------------------------------------------------------------
    |
    | Here you register the parsers for compiling the url. The parsers can match
    | a given pattern on url path parameter, and execute some logic to return
    | some expected behavior. Tecnically the parser just create the logic to match
    | dynamic parameters and add dynamic arguments. But the formatter class that
    | really executes the logic.
    | 
    */

    'parsers' => [
        ColonParser::class => [
            YearFormatter::class,
            DayFormatter::class,
            MonthFormatter::class,
            RandomFormatter::class,
            TimestampsFormatter::class
        ]
    ]
];
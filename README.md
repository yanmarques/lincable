# Lincable 
[![Build Status](https://travis-ci.org/yanmarques/lincable.svg?branch=dev)](https://travis-ci.org/yanmarques/lincable)
 [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yanmarques/lincable/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/yanmarques/lincable/?branch=dev) 

Link the Eloquent model to an uploaded file, store the file on storage and then save the url on model. :cloud:

# Table Of Contents

* [Basic Usage](#basic-usage)
* [Getting Started](#getting-started)
    - [Installing](#installing)
    - [Register the Service Provider](#register-the-service-provider)
    - [Publish Configuration](#publish-configuration)
    - [Configuring The Model](#configuring-the-model)
    - [FileRequest](#filerequest)
    - [Parsers and Formatters](#parsers-and-formatters)
    - [UrlGenerator](#urlgenerator)
* [Testing](#testing)
* [Lincese](#license)

# Why this?

The lincable package handle the file upload, store the file on some disk storage and link the model with the uploaded file. The whole upload process until getting the file saved and linked with the model, is well configured to provide you assistance at all steps. The link generated for the model can have dynamic parameters to be implicitly injected with the model attributes, or execute any custom logic. 

![Database lik image](https://www.designbombs.com/wp-content/uploads/2016/04/database-connection-1024x425.jpg)

## Basic Usage

When working with file uploads, generally we expect some data types on upload, or we need to move the temporary file created by PHP to a correct location. Controller's action should not have file manipulation logic, anyway this is totaly common to have file manipulation on controller action, but putting these logic on controller make the logic becomes duplicate code once this kind of manipulation is generally the same. `FileRequest` class solves this problem, removing the upload logic from controller. Sure, the class also carries the rules to validate the file and execute an event method before saving the file. Using the file request as parameter of the action ensures the data type been uploaded.

Here is the action to execute the upload.

```php

/**
 * Upload an image to application.
 *
 * @param  ImageFileRequest $image
 * @return Response
 */
public function upload(ImageFileRequest $image) 
{
    // The ImageFileRequest contains the current request.
    $request = $image->getRequest();
    
    // Create the image model on database with request attributes.
    $image = \App\Image::create($request->all());
    
    // The model uses the lincable trait, which has the link method
    // that store the file and generate a url for the model.
    $image->link($image);
    
    $image->preview; // https://your-cloud-storage.com/media/foo/123/bar/321/e1wPJQmQpFPOaQ238fglQHnrxzv2uK8joPyozv9i.jpg
}

```

# Getting Started

## Installing

You can install using [composer](https://getcomposer.org/):
```bash
$ composer require yanmarques/lincable
```
Or using git:
```bash
$ git clone git@github.com:yanmarques/lincable.git
```
Or you can just download the binaries [releases](https://github.com/yanmarques/lincable/releases).

## Register The Service Provider

You need to register the package with laravel, adding the service provider on the application providers. 
At the `config/app.php` add this to your providers:

```php

'providers' => [
    ...
    /*
     * Package Service Providers...
     */
    Lincable\Providers\MediaManagerServiceProvider::class,
]

```

## Publish Configuration

Basically, you must to configurate the lincable file to get everything running cool. This configuraton file will be available at `config/lincable.php` after you have published the service container assets with:

```bash
$ php artisan vendor:publish --provider="Lincable\Providers\MediaManagerServiceProvider"
```

Now we have registered the package on your environment, and we need to start configuring how lincable should work. The package is very flexible given you control to decide how to use it, there is no a only way.   

## Configuring The Model

The eloquent model will be responsable to link the file. The link will be generated based on the lincable file configuration, by the [url generator](#urlgenerator). On model we just need to add the [lincable trait](#lincable-trait) to support linking the model with a file.
Suppose we have an image model which has an `id` and `user_id` attributes, both uuid type.

```php

use Lincable\Eloquent\Trait\Lincable;

class Image extends Model
{
    use Lincable;

    protected $fillable = ['user_id'];
}

```

Once we have our model, we need to define the url generated for the model. We must set the urls on `config/lincable.php`. Urls is the list with all model urls configuration. Each model has an url, and this one, by default has dynamic parameters to be injected with the model attributes, see [parsers and formatters](#parsers-and-formatters) for more details of how to use default dynamic parameters. For example, we want to save the file in an url that contains the `user_id` and the model `id`, something like that this path: `users/user-id-here/ìmages/id-here/`. We can inject model attributes on url using the url dynamic parameters.

Here the file `config/lincable.php` configuration.

```php

return [
    ...
   'urls' => [
        \App\Image::class => 'users/:user_id/images/:id'
    ]
];

```

Ok, now we create the controller to handle the upload. Laravel uses the containter dependency injection to resolve classes, methods, closures, etc..., the controller action registered on route has this definition as well. As seen before at [basic usage](#basic-usage), the controller should receive the file request as argument, this is nice because the file request must be booted with the current request to work, and when the container resolves a file request, it boots the object with the request. The code at basic usage ensures the file type, because as explained before, the file request as service must be booted with the current request, and this process is necessary to validate the file with the rules. Once the file is not valid, we do not even execute the action code.  

The `ImageFileRequest` extends from the `FileRequest` abstract class that actually handles the validation and configuration on file. When file request is booted with the curren request stack, we try to load the file uploaded from class name, in this case the file parameter would be `image`. The only abstract method to implement is the `rules`, that returns the validation rules for the uploaded file, see laravel [validations](https://laravel.com/docs/5.6/validation#rule-mimes) for more details. 

## FileRequest

The controller to upload the files will handle the file upload and storing it on cloud and saving the link on the database. The file request will be a file manager defining how the file upload will be performed and what kind of data type is expected for the controller upload. This enforces the data type and rules for the file upload, making generic file requests that can work with more other controllers that receives same file types. 

```php

/**
 * Upload a image to application.
 * 
 * @param  \App\FileRequests\ImageFileRequest $image
 * @return response
 */
public function upload(ImageFileRequest $image) 

```

On the `App\FileRequests\ImageFileRequest` class we define rules for images upload on the application, and what to do with the file before effectively storing it on disk storage. For example, we want to resize the image upload before storing it. We are calling `Image` facade from [intervention](http://image.intervention.io) image manipulation.

```php

use Image;
use Lincable\Http\FileRequest;

class ImageFileRequest extends FileRequest
{
    protected function rules()
    {
        return 'required:jpg,png';
    }
     
    protected function beforeSend($file)
    {
        // The do not need to return a file, because the image has 
        // been saved an the changes will reflect.
        Image::make($file)->resize(600, 400)->save();
    }
}

```



## Parsers and Formatters

To allow dynamic parameters on the url we must provide a Parser class to define how the parameters will be presented on the url. There is no only way, you should create your own. By default we provide the `\Lincable\Parsers\ColonParser` which is a parser implementation for parameters beginning with a colon, very simple. Parsers just extract dynamic parameters from parts of the url, but the formatter that really execute the parameter logic. By default we add some formatters for the colon parser:

* `year`: Returns the current year.
* `month`: Returns the current month.
* `day`: Returns the current day.
* `random`: Returns a random string of 32 length.
* `timestamps`: Returns the hashed current UNIX timestamp.

The parser allows you to pass parameters for the formatter using the regex pattern to split the matches. You can also pass an anonymous function to the parser with a class dependency, and the container will resolve the class instance.

Suppose we want to store a file in diferente locations depending on a token or id on the request. We can create a formatter to execute this task with the request.
```php

$colonParser->addFormatter(function (Request $request) {
    if ($request->user()->isBoss()) {
        return 'boss-location';
    }
    
    return 'baz';
}, 'customLocation');

$url = 'foo/:customLocation';

// Is user on request is the boss.
'foo/boss-location/dqiojqwdij.zip'


// The user on request is not the boss.
'foo/baz/dqiojqwdij.zip';

```
You can also configure a formatter class on lincable configuration. First we create the formatter, for example, pretend we want to create the link based on an api service. We need an especialized class to solve this problem. Let's create a dummy not functional class that retrieves and identifier for the current authenticated user on request from a client api.

```php

namespace App\Formatters;

...

class GetApiIdFormatter
{
    /**
     * Attributes initialized here. 
     */
    
    public function __construct(ApiClient $api, Config $params) 
    {
        $this->api = $api;
        $this->setConfig($config);
        
        // More function tasks.
    }
    
    /**
     * More complex methods here. 
     */
     
    public function format(Request $request)
    {
        return $this->getIdForUser($request->user());
    }
}

```

Now we add the formatter class to lincable configuration. First thing, you must understand how a formatter work and what the parser function in [parsers and formatters](#parsers-and-formatters). Now you know, we add the formatter to the default package parser, the `ColonParser`. 

```php

return [
    ...
    
    default_parsers => [
         \Lincable\Parsers\ColonParser::class => [
            \Lincable\Formatters\YearFormatter::class,
            \Lincable\Formatters\DayFormatter::class,
            \Lincable\Formatters\MonthFormatter::class,
            \Lincable\Formatters\RandomFormatter::class,
            \Lincable\Formatters\TimestampsFormatter::class,
            
            /**
             * Here we add the formatter
             */
             \App\Formatters\GetApiIdFormatter::class
        ]
    ] 
];

```

## UrlGenerator

The url generator compiles a given url for the model instance, based on url configuration.  

```php

$file = File::create(['name' => 'foo.zip']); // Create the file model.
$file->toArray(); // ['id' => 123, 'name' => 'foo.zip', 'preview' => null]
$urlConf = new UrlConf; // Create the model url configuration.
$urlConf->push(File::class, ':year/:month/:id/:name');
$generator = new UrlGenerator($urlCompiler, $parsers, $urlConf); 
$preview = $generator->forModel($file)->generate(); // Generate the url for the model from configuration.
$file->update(compact('preview')); // ['id' => 123, 'name' => 'foo.zip', 'preview' => '2018/07/123/foo.zip']


```

# Testing

We make the world a better place with tests :octocat:

```bash
$ vendor/bin/phpunit
```

# License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

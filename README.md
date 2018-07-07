# Lincable 
[![Build Status](https://travis-ci.org/yanmarques/lincable.svg?branch=dev)](https://travis-ci.org/yanmarques/lincable)
 [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yanmarques/lincable/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/yanmarques/lincable/?branch=dev) 

Create a link with Eloquent to an uploaded file and manage storing this file in some cloud storage. :cloud:

# Why this?

My goal is to design a package to handle the file upload, link the model with the uploaded file and then store the file on the cloud. The url has customizable formatters to execute some logic when generating the url, but you can create your own. When creating the model or uploading the file to cloud storage, we are not free of errors, so the creation and upload are covered to register unexpected behaviours and rollback taks.  

![Database lik image](https://www.designbombs.com/wp-content/uploads/2016/04/database-connection-1024x425.jpg)

## Proposals

* The package will allow you to easy configurate the path map of your model url.
* Support for dynamic parameters.
* Support for dynamic code execution on when compiling the path.
* Support for relating an Eloquent model with a link preview.
* Support for receiving a file request class on controller action, and attaching it to a model.

Sounds nice? Let's develop this! :smile:

## Basic Usage

You will specify what type of data type to be uploaded and create the model from it. The preview will be auto generated based on url configuration.

```php

public function upload(ImageFilRequest $imageUploaded) {
    $image = \App\Image::createFromFileRequest($imageUploaded);
    $image->id; // 1
    $image->filename; // profile.jpg
    $image->preview; // https://your-cloud-storage.com/your/path/1/profile.jpg
}

```

# Getting Started

## Installing

You can install cloning the project with git:
```bash
$ git clone git@github.com:yanmarques/lincable.git
```
Or you can just download the binaries [releases](https://github.com/yanmarques/lincable/releases).

> *Note: For now the package is not configured with Laravel as we are in development process. All you can do is to test.
> The usage described below is experimental and can change over the time*. 

The first step is to register the url for your model on `config/lincable.php`. By default, the url has dynamic parameters to allow you to execute some logic when generating the url. To specify a dynamic parameter we just type a colon on the start of the parameter and voilá (see [parsers and formatters](#parsers-and-formatters)). For now, we can register the schema of how the url will be generated for the model. 

Suppose we have an `Image` model with an ID and filename attributes. We have configurated the url to change the `:id` and `:filename` to the same attributes value in model. 

```php

return [
    ...
    
   'urls' => [
        \App\Image::class => 'your/path/:id/:filename'
    ]
];

```

Now we create the controller to handle the upload. Laravel uses the containter dependency injection to auto resolve methods arguments, the controller method registered on route has this definition as well, then we can put the file request we want to receive from on controller method. 

```php

public function upload(ImageFilRequest $imageUploaded) {
    $image = \App\Image::createFromFileRequest($imageUploaded);
    $image->id; // 1
    $image->filename; // profile.jpg
    $image->preview; // https://your-cloud-storage.com/your/path/1/profile.jpg
}

```

The ```ImageFileRequest``` is the class to handle the file uploaded. It extends from the `FileRequest` abstract class that actually handles the validation and configuration on file. When the request with the file comes, we try to load the file from class name, in this case the file parameter would be `image`. The good thing about using the file request on controller method is that we define what kind of data we expect on upload. The only abstract method to implement is the `rules`, to validate the file. The `image` attribute on request must be validate the rules. See laravel [validations](https://laravel.com/docs/5.6/validation#rule-mimes). But others methods are available to customize how to perform the uplaod. See [FileRequest](#filerequest).

```php

use Lincable\Http\FileRequest;

class ImageFileRequest extends FileRequest
{
    public function rules() 
    {
        return [
             'required|mimes:jpeg,bmp,png'
        ];
    }
    
    public function boforeSend($file)
    {
        // Make file operations before send it to storage.
        // You can return a new file here.
    }
}

```

# Parsers and Formatters

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
    
    return 'foo';
}, 'customLocation');

$url = ':customLocation/:filename';

// Is user on request is the boss.
'boss-location/dqiojqwdij.zip'


// The user on request is not the boss.
'foo/dqiojqwdij.zip';

```

# UrlGenerator

The url generator can compile the model url injecting the model attributes on the url parameters. This class is responsable for the dynamic generation of the url using the a compiler, parsers and the url configuration. 

```php

$file->id; // 123
$file->name; // foo.zip
$urlConf = new UrlConf;
$urlConf->push(File::class, ':year/:month/:id/:name');
$generator = new UrlGenerator($urlCompiler, $parsers, $urlConf);
$generator->forModel($file)->generate(); // '2018/07/123/foo.zip'

```

# Testing

We make the world a better place with tests :octocat:

```bash
$ ./vendor/php/unit
```

# License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

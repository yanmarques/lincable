# Lincable
[![Build Status](https://travis-ci.org/yanmarques/lincable.svg?branch=dev)](https://travis-ci.org/yanmarques/lincable)
 [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yanmarques/lincable/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/yanmarques/lincable/?branch=dev) 
 
Storage manager for laravel Eloquent.

# Why Lincable?

How do you manage storing uploaded files with dynamic link generation on some cloud storage? And when you need also to relate this file with a model on your database? When storing files on a dedicated server, like Amazon for example, we have to specify the path where the object will be stored, which is the same for further access. This can get a little tricky when you have multiple definitions on the link, like IDs, timestamps, hash, etc...  

## Proposals

* The package will allow you to easy configurate the path map of your model url.
* Support for dynamic parameters.
* Support for dynamic code execution on when compiling the path.
* Support for relating an Eloquent model with a link preview.
* Support for receiving a file request class on controller action, and attaching it to a model.

Sounds nice? Let's develop this! :smile:

## Basic Usage

You must register how do you want the link will be generated for your model. By default the url accepts dynamic parameters with a colon and the formatter name (see [#formatters]) or the model attribute.  

The configuration file ```config/lincable.php```.
```php

return [
    ...
    
   'urls' => [
        \App\Image::class => 'your/path/:id/:filename'
    ]
];

```

Now we have a controller to upload an image from user and save the image on some cloud storage, and also create a link with the model and the file.

```php
public function upload(ImageFilRequest $imageUploaded) {
    $image = \App\Image::createFromFileRequest($imageUploaded);
    $image->id; // 1
    $image->filename; // profile.jpg
    $image->preview; // https://your-cloud-storage.com/your/path/1/profile.jpg
}
```

The ```ImageFileRequest``` is the class to declarative perfom commom tasks for uploaded files and allow us to modify the image as we want before actually uploading it to the cloud. 

The ```rules``` method must be implemented, to validate the file. See laravel [validations](https://laravel.com/docs/5.6/validation#rule-mimes).

```php

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

public class ImageFileRequest extends FileRequest
{
    public function rules(UploadedFile $file) 
    {
        return [
             'mimes:jpeg,bmp,png'
        ];
    }
    
    public function beforeSend(File $file)
    {
        // Make file operations before send it to storage.
    }
}

```

The ```image``` attribute on request must be validate the rules.

# License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

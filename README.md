# Lincable
[![Build Status](https://travis-ci.org/yanmarques/lincable.svg?branch=dev)](https://travis-ci.org/yanmarques/lincable)
 [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yanmarques/lincable/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/yanmarques/lincable/?branch=dev) 
 
Storage manager for laravel Eloquent.

# Why Lincable?

How do you manage storing uploaded files with dynamic link generation on some cloud storage? And when you need also to relate this file with a model on your database? When storing files on a dedicated server, like Amazon for example, we have to specify the path where the object will be stored, which is the same for further access. This can get a little tricky when you have multiple definitions on the link, like IDs, timestamps, hash, etc... 

When 

## Proposals

* The package will allow you to easy configurate the path map of your model url.
* Support for dynamic parameters.
* Support for dynamic code execution on when compiling the path.
* Support for relating an Eloquent model with a link preview.
* Support for receiving a file request class on controller action, and attaching it to a model.

Sounds nice? Let's develop this! :smile:

# License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

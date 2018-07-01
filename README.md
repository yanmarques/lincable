# Lincable

Storage link generator for laravel Eloquent.

# Why Lincable?

How do you manage dynamic link generation when storing files on some cloud storage? When storing files on a dedicated server, like Amazon for example, we have to specify the path where the object will be stored, which is the same for further access. This can get a little tricky when you have multiple definitions on the link, like IDs, timestamps, hash, etc..

## Pruposals

* The package will allow you to easy configurate the path map.
* Support for dynamic parameters.
* Support for dynamic code execution on when compiling the path.
* Support for relating an Eloquent model with a link preview.

# License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

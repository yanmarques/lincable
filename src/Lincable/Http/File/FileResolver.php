<?php

namespace Lincable\Http\File;

use Illuminate\Http\Request;
use Lincable\Http\FileRequest;
use Illuminate\Container\Container;
use Illuminate\Http\File as IlluminateFile;
use Lincable\Exceptions\NotResolvableFileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class FileResolver
{
    /**
     * Resolve the file object to a symfony file, handling the
     * file request operations.
     *
     * @throws \Lincable\Exceptions\NotResolvableFileException
     *
     * @param  mixed $file
     * @return \Illuminate\Http\File
     */
    public static function resolve($file)
    {
        switch (true) {
            case is_string($file):
                $file = new SymfonyFile($file);
                break;
            case $file instanceof FileRequest:
                $file = static::resolveFileRequest($file);
                break;
            case $file instanceof UploadedFile:
                $filename = $file->hashName();
                $file = $file->move(config('lincable.temp_directory'), $filename);
                break;
            case $file instanceof IlluminateFile:
                return $file;
                break;
            case $file instanceof Symfonyfile: break;
            default:
                throw new NotResolvableFileException($file);
        }

        return static::toIlluminateFile($file);
    }

    /**
     * Convert a symfony file to illuminate file.
     *
     * @param  \Symfony\Component\HttpFoundation\File\File $file
     * @return \Illuminate\Http\File
     */
    public static function toIlluminateFile(SymfonyFile $file)
    {
        return new IlluminateFile($file->getPathName());
    }

    /**
     * Handle a file request and resolve to a file.
     *
     * @param  \Lincable\Http\FileRequest $file
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public static function resolveFileRequest(FileRequest $file)
    {
        // Get the global container instance.
        $app = Container::getInstance();

        if (! $file->isBooted()) {

            // Boot the file request with the current request.
            $file->boot($app->make(Request::class));
        }

        return $file->prepareFile($app);
    }
}

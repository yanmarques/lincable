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
     * @param  mixed $file
     * @return \Illuminate\Http\File
     * 
     * @throws \Lincable\Exceptions\NotResolvableFileException
     */
    public static function resolve($file)
    {
        switch (true) {
            case is_string($file):
                $file = new SymfonyFile($file);
                break;
            case $file instanceof FileRequest:
                $file = $file->prepareFile();
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
}

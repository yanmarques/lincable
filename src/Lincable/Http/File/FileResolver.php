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
     */
    public static function resolve($file)
    {
        if ($file instanceof IlluminateFile) {
            return $file;
        }

        return static::toIlluminateFile(static::resolveSymfonyFile($file));
    }

    /**
     * Prepare the file .
     *
     * @param  mixed  $file
     * @return \Symfony\Component\HttpFoundation\File\File
     * 
     * @throws \Lincable\Exceptions\NotResolvableFileException
     */
    public static function resolveSymfonyFile($file)
    {
        if (is_string($file)) {
            return new SymfonyFile($file);
        }
            
        if ($file instanceof FileRequest) {
            return $file->prepareFile();
        }
            
        if ($file instanceof UploadedFile) {
            return $file->move(
                FileFactory::getTemporaryDirectory(), 
                $file->hashName()
            );   
        }
        
        if ($file instanceof Symfonyfile) {
            return $file;
        }

        throw new NotResolvableFileException($file);
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

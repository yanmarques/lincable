<?php

namespace Lincable\Http\File;

use Illuminate\Http\File;

class FileFactory
{
    /**
     * Create a temporary file from specified original content.
     *
     * @param  mixed  $data
     * @param  string  $originalFileName
     * @param  mixed|null  $flags
     * @return void
     */
    public static function createTemporary(
        $data,
        string $originalFileName = null, 
        $flags = null
    ) {
        // Generate a temp file from configuration and file name.
        $filename = static::getTemporaryDirectory(
            str_random().'.'.static::extension($originalFileName)
        );

        file_put_contents($filename, $data, $flags);
        
        // Handle stream resource.
        if (is_resource($data)) {
            fclose($data); 
        }

        return new File($filename);
    }

    /**
     * Return the extension from filename.
     *
     * @param  string  $filename
     * @return string
     */
    public static function extension(string $filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Return the file name from path.
     *
     * @param  string  $path
     * @return string
     */
    public static function fileName(string $path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * Return the configured temporary directory. You may pass
     * a file name to build some custom path.
     *
     * @param  string|null  $fileName
     * @return void
     */
    public static function getTemporaryDirectory(string $fileName = null)
    {
        $configuredTemp = config('lincable.temp_directory');

        $directory = str_finish(
            $configuredTemp === null 
                ? sys_get_temp_dir() 
                : (string) $configuredTemp, 
            DIRECTORY_SEPARATOR
        );
    
        if ($fileName) {
            return $directory.$fileName;
        }

        return $directory;
    }
}
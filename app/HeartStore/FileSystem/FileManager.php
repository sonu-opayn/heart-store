<?php

namespace App\HeartStore\FileSystem;

abstract class FileManager
{   
    /**
     * @param String $file File name
     * @param String $uploadPath Directory to upload the file.
     */
    abstract public function upload($file, $uploadPath);

    /**
     * @param String $file File name to generate the public url
     */
    abstract public function getPublicUrl($name);
}
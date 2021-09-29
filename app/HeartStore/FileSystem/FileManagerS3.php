<?php

namespace App\HeartStore\FileSystem;

use App\HeartStore\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;

class FileManagerS3 extends FileManager
{
    private $prefixPath = '/HeartStore/uploads/';

    public function upload($file, $uploadPath) 
    {
        $s3 = Storage::disk('s3');
        
        $withoutPrefix = $uploadPath . '/' . time() . '-' . $file->getClientOriginalName();
        $imageName = $this->prefixPath . $withoutPrefix;
        $imageName = Util::normalizePath($imageName);
        
        $s3->put($imageName, file_get_contents($file), 'public-read');
        $s3->url($imageName);        
        
        return $withoutPrefix;
    }

    public function getPublicUrl($name) 
    {
        if(empty($name)) {
            throw new GeneralException("File name is empty.");
        }

        return "https://s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/" .  env('AWS_BUCKET') . "{$this->prefixPath}{$name}";
    }
}
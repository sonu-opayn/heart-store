<?php

namespace App\HeartStore\FileSystem;

use App\HeartStore\Exceptions\GeneralException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class FileManagerDisk extends FileManager
{
    public function upload($file, $uploadPath) 
    {
        $path = $file->storeAs($uploadPath, uniqid() . '-' . $file->getClientOriginalName(), 'public_uploads');
        return $path;
    }

    public function getPublicUrl($name) 
    {
        if(empty($name)) {
            throw new GeneralException("File name is empty.");
        }

        return asset('uploads/' . $name);
    }
}
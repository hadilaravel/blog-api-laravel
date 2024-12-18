<?php

namespace App\Service;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ShareService
{

    public static function uploadFileStorage($file ,$folder)
    {
        $name = time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->putFileAs($folder, $file, $name);

        $imageName =  $folder . '/' . $name;

        return  $imageName;
    }

    public static function uploadFilePublic($file ,$folder)
    {
        $name = time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('pub')->putFileAs($folder, $file, $name);

        $imageName = $folder . '/' . $name;

        return $imageName;
    }

    public static function deleteFileStorage($request)
    {
        if(Storage::exists($request))
        {
            Storage::delete($request);
        }
    }

    public static function deleteFilePublic($request)
    {
        if(File::exists($request))
        {
            File::delete($request);
        }
    }

}

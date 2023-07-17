<?php

namespace App\CPU;

class HelperImage
{
   public static function saveImage($folder, $file)
   {
        $extension = $file->getClientOriginalExtension();
        $imageName = md5(date('YmdHis') . uniqid()) . '.' . $extension;
        
        $file->move(public_path($folder),$imageName);

        return $imageName;
    }

    /**
     * Update image file
     * @param string $folder
     * @param $file
     * @param string|null $oldPath
     * 
     * @return $imagePath
     */
    public static function updateImage($folder, $file, $oldPath = null)
    {
        self::removeOldImage($oldPath);

        $extension = $file->getClientOriginalExtension();
        $imageName = md5(date('YmdHis') . uniqid()) . '.' . $extension;

        $file->move(public_path($folder), $imageName);

        return $imageName;
    }

    /**
     * remove old image
     * @param string|null $oldPath
     * 
     * @return void
     */
    public static function removeOldImage($oldPath)
    {
        // remove old file
        if($oldPath && file_exists(public_path($oldPath))) {
            unlink(public_path($oldPath));
        }
    }
}

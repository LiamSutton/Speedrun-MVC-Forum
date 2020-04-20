<?php


/**
 * Class FileUpload
 */
class FileUpload
{
    /**
     * FileUpload constructor.
     *
     */
    public function __construct()
    {
    }
    
    /**
     * @param $image - the image to upload
     * @return bool - false if it fails, not sophisticated but legit forgot about this :(
     */
    public static function uploadImage($image)
    {
        $dir = "images/";
        $uploadFile = $dir .basename($_FILES[$image]['name']);

        if (move_uploaded_file($_FILES[$image]['tmp_name'], $uploadFile))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
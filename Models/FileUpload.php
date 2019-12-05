<?php


class FileUpload
{
    public function __construct()
    {
    }

    public static function uploadImage($image)
    {
        $postImage = $_FILES[$image]['name'];
        $dir = "images/";
        $uploadFile = $dir .basename($_FILES[$image]['name']);

        if (move_uploaded_file($_FILES[$image]['tmp_name'], $uploadFile))
        {
            // do nothing
        }
        else
        {
            // error handling
        }
    }
}
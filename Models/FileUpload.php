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

//        if ($_FILES['user_avatar']['error'] == 0)
//            {
//                $avatar = $_FILES['user_avatar']['name'];
//                $dir = 'images/';
//                $uploadfile = $dir . basename($_FILES['user_avatar']['name']);
//                // TODO: Add Validation, Error Handling
//                // use is_uploaded method to check it was uploaded via HTTP POST
//                if (move_uploaded_file($_FILES['user_avatar']['tmp_name'], $uploadfile))
//                {
//                    echo "<h1>File uploaded and moved</h1>";
//                }
//
//else
//{
//    echo $_FILES['user_avatar']['error'];
//}
}
<?php
session_start();

require_once("Models/ReCaptcha.php");
require_once("Models/FileUpload.php");
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

if (isset($_POST['submit']))
{
    // User completed the reCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0)
    {
        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        //User successfully completed ReCaptcha
        if ($reCaptchaResult)
        {
            // Check if file was uploaded
            if ($_FILES['post_image']['error'] == 0)
            {
                // TODO: Check the image is of certain file types {.jpg, .png, .jpeg} ect...
                $postImage = $_FILES['post_image']['name'];
                FileUpload::uploadImage("post_image");
            }
            else
            {
                $postImage = null;
            }
            // Instantiate user and post data objects
            $userDataset = new UserDataset();
            $postsDataset = new PostDataset();
            
            // Get User
            $user = $userDataset->getUser($_SESSION['username']);
            
            // get data required to create a Post
            $posterID = $user->getId();
            $title = $_POST['title'];
            $content = $_POST['content'];
            $categoryID = $_GET['categoryID'];

            // Commit it to DB
            $postsDataset->createPost($posterID, $title, $content, $postImage, $categoryID);
        }
        else
        {
            die(ReCaptcha::$FAILED);
        }
    }
    else
    {
        die(ReCaptcha::$NOT_COMPLETED);
    }

    // Redirect
    //TODO: Hard coding this might not be the best way
    header("Location: posts.php?categoryID=$categoryID&page=1&limit=5&sort=1&title=");
}
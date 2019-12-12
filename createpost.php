<?php
session_start();

require_once("Models/ReCaptcha.php");
require_once("Models/FileUpload.php");
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

if (isset($_POST['submit']))
{
    // User completed the reCaptcha
    if (strlen(htmlentities($_POST['g-recaptcha-response'])) != 0)
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
            $title = htmlentities($_POST['title']);
            $content = htmlentities($_POST['content']);
            $categoryID = htmlentities($_GET['categoryID']);

            // whether creating the post is successfull
            $success = $postsDataset->createPost($posterID, $title, $content, $postImage, $categoryID);

            // redirect error
            if (!$success)
            {
                header("Location: posts.php?&categoryid=$categoryID&page=1&limit=5&date=1&comment=2&title&failed");
            }
        }
        else
        {// redirect error
            header("Location: posts.php?&categoryid=$categoryID&page=1&limit=5&date=1&comment=1&title&recaptcha");
            exit();
        }
    }
    else
    {
        // redirect error
        header("Location: posts.php?&categoryID=$categoryID&page=1&limit=5&date=1&comment=1&title&recaptcha");
        exit();
    }

    // Redirect success
    header("Location: posts.php?categoryID=$categoryID&page=1&limit=5&date=1&comment=1&title&posted");
}
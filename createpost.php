<?php
session_start();
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");
require_once("Models/ReCaptcha.php");

if (isset($_POST['submit']))
{
    // User completed the reCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0)
    {
        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        //User successfully completed ReCaptcha
        if ($reCaptchaResult)
        {
            // Instanciate user and post data objects
            $userDataset = new UserDataset();
            $postsDataset = new PostDataset();
            
            // Get User
            $user = $userDataset->getUser($_SESSION['username']);
            
            // get data required to create a Post
            $posterID = $user->getId();
            $title = $_POST['title'];
            $content = $_POST['content'];

            // Commit it to DB
            $postsDataset->createPost($posterID, $title, $content);
        }
        else
        {
            die("ReCaptcha Failed");
        }
    }
    else
    {
        die("ReCaptcha must be completed");
    }

    // Redirect
    header("Location: posts.php");
}
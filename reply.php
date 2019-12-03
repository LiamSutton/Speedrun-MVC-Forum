<?php
session_start();
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");
require_once ("Models/ReCaptcha.php");

if (isset($_POST['submit']))
{
    // User completed the ReCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0)
    {
        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        // ReCaptcha validated
        if ($reCaptchaResult)
        {
            // Instanciate user and post data objects
            $userDataset = new UserDataset();
            $postDataset = new PostDataset();

            // Get the User making the Reply
            $user = $userDataset->getUser($_SESSION['username']);

            // TODO: Construct post object maybe?
            // Get data required to create a Reply
            $mainId = $_GET['mainid'];
            $id = $_GET['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $p_parentID = $_GET['id'];

            // Commit new Reply to the db
            $postDataset->createReply($user->getId(), $_POST['title'], $_POST['content'], $p_parentID);
        }
        else
        {
            die("ReCaptcha Failed");
        }
    }
    else
    {
        die("ReCaptcha Must Be Completed");
    }

    // Redirect
    header("Location: fullpost.php?id=$mainId");
}
<?php
session_start();
require_once ("Models/ReCaptcha.php");
require_once ("Models/FileUpload.php");
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");


if (isset($_POST['submit']))
{

    // get info to create reply
    $id = $_GET['id'];
    $title = htmlentities($_POST['title']);
    $content = htmlentities($_POST['content']);
    $p_parentID = $_GET['id'];
    $categoryID = $_GET['categoryID'];

    // User completed the ReCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0)
    {
        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        // ReCaptcha validated
        if ($reCaptchaResult)
        {

            // Instantiate user and post data objects
            $userDataset = new UserDataset();
            $postDataset = new PostDataset();

            // Get the User making the Reply
            $user = $userDataset->getUser($_SESSION['username']);



            // returns whether the new reply was created successfully
            $success = $postDataset->createReply($user->getId(), $_POST['title'], $_POST['content'], $p_parentID, $categoryID);

            // redirect error
            if (!$success)
            {
                header("Location: fullpost.php?id=$p_parentID&failed");
                exit();
            }
        }
        else
        {
            // redirect error
            header("Location: fullpost.php?id=$p_parentID&recaptcha");
            exit();

        }

    }
    else
    {
        // redirect error
        header("Location: fullpost.php?id=$p_parentID&recaptcha");
            exit();
    }

    // Redirect success
      header("Location: fullpost.php?id=$p_parentID&posted");
    exit();
}
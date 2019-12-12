<?php
session_start();
require_once ("Models/ReCaptcha.php");
require_once ("Models/FileUpload.php");
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

// TODO: rename to createReply? for consistency
if (isset($_POST['submit']))
{

    $id = $_GET['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $p_parentID = $_GET['id'];
    $categoryID = $_GET['categoryID'];

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

            // Dont need mainID for now
//          $mainId = $_GET['mainid'];;

            // Commit new Reply to the db
            $success = $postDataset->createReply($user->getId(), $_POST['title'], $_POST['content'], $p_parentID, $categoryID);
            if (!$success)
            {
                header("Location: fullpost.php?id=$p_parentID&failed");
            }
        }
        else
        {
            header("Location: fullpost.php?id=$p_parentID&recaptcha");
            exit();

        }

    }
    else
    {
        header("Location: fullpost.php?id=$p_parentID&posted");
            exit();
    }

    // Redirect
//    header("Location: fullpost.php?id=$p_parentID?posted");
      header("Location: fullpost.php?id=$p_parentID&posted");
    exit();
}
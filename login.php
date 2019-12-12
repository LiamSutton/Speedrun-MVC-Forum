<?php
session_start();
require_once ("Models/UserDataset.php");
require_once ("Models/ReCaptcha.php");

if (isset($_POST['submit']))
{
    // if the user completed the reCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0)
    {

        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);
        
        // User successfully completed the reCaptcha
        if ($reCaptchaResult)
        {
            // Parse the potential login details
            $username =  htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            // instantiate the user data class
            $userData = new UserDataset();

            // attempt to login the user
            if ($userData->Login($username, $password))
            {
                // User has successfully Logged in
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $userData->getUser($username)->getId();
            }
            else
            {
                // user couldn't log in
                header("Location: index.php?failed");
                exit;
            }
        }
        // User failed the reCaptcha
        else
        {
            header("Location: index.php?recaptcha");
            exit();

        }
    }
    // User didn't complete the reCaptcha
    else
    {
        header("Location: index.php?recaptcha");
        exit();
    }
}

// Redirect the user to index page
header("Location: index.php");
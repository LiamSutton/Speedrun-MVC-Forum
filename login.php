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
            // TODO: Sanitize input against SQL injection
            $username = $_POST['username'];
            $password = $_POST['password'];

            // instanciate the user data class
            $userData = new UserDataset();

            // attempt to login the user
            if ($userData->Login($username, $password))
            {
                // User has successfully Logged in
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $userData->getUser($username)->getId();
            }
        }
        // User failed the reCaptcha
        else
        {
            die("ReCaptcha Failed");
        }
    }
    // User didn't complete the reCaptcha
    else
    {
        die("ReCaptcha Must Be Completed");
    }
}

// Redirect the user to index page
header("Location: index.php");
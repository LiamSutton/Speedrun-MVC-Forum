<?php

require_once("Models/ReCaptcha.php");
require_once("Models/UserDataset.php");
session_start();
if (isset($_POST['submit'])) {
    
    // User completed ReCaptcha
    if (strlen($_POST['g-recaptcha-result']) != 0) {

        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        // User validated from reCaptcha
        if ($reCaptchaResult)
        {
            // Setup user details
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            // Instanciate data class
            $userDataset = new UserDataset();

            // Insert new user into the db
            $userDataset->createUser($username, $password, $firstname, $lastname, 'https://robohash.org/test');
            
            // Log the new user in
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $userDataset->getUser($username)->getId();

            // redirect to index page
            header("Location: index.php");
        }
        // User failed ReCaptcha
        else
        {
            die(ReCaptcha::$FAILED);
        }
    }
    else
    {
        die(ReCaptcha::$NOT_COMPLETED);
    }
 }

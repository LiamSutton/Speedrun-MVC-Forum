<?php

require_once("Models/ReCaptcha.php");
require_once("Models/FileUpload.php");
require_once("Models/UserDataset.php");
session_start();
if (isset($_POST['submit'])) {
    
    // User completed ReCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0) {

        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        // User validated from reCaptcha
        if ($reCaptchaResult)
        {

            // error 0: no error
            // possibly check if it has error 4: no image uploaded?
            if ($_FILES['user_avatar']['error'] == 0)
            {
                FileUpload::uploadImage('user_avatar');
                // maybe get the function to return the avatar file name?
                $avatar = $_FILES['user_avatar']['name'];
            }
            else
            {
                $avatar = 'default_avatar.jpg';
            }
            // Setup user details
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            // Instanciate data class
            $userDataset = new UserDataset();

            // Insert new user into the db
            $userDataset->createUser($username, $password, $firstname, $lastname, $avatar);
            
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
 else
 {
     echo "<h1>Not Authorised to access this</h1>";
 }

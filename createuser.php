<?php

require_once("Models/ReCaptcha.php");
require_once("Models/UserDataset.php");
session_start();
if (isset($_POST['submit'])) {
    
    // User completed ReCaptcha
    if (strlen($_POST['g-recaptcha-response']) != 0) {

        $reCaptchaResult = ReCaptcha::getReCaptchaResult($_POST['g-recaptcha-response']);

        // User validated from reCaptcha
        if ($reCaptchaResult)
        {
            $avatar = "";
            
            if ($_FILES['user_avatar']['name'] != 'none')
            {
                $avatar = $_FILES['user_avatar']['name'];
                $dir = 'images/';
                $uploadfile = $dir . basename($_FILES['user_avatar']['name']);
                // TODO: Add Validation, Error Handling
                if (move_uploaded_file($_FILES['user_avatar']['tmp_name'], $uploadfile))
                {
                    echo "<h1>File uploaded and moved</h1>";
                }
                else
                {
                    echo $_FILES['user_avatar']['error'];
                }
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

<?php
require_once("Models/UserDataset.php");
session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST['g-recaptcha-response'])) {
        $resp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc0psUUAAAAAOuaEeY6Q4mEEzPwOaZnPKz2FUbG&response=" . $_POST['g-recaptcha-response']);
        $respData = json_decode($resp, true);
        // Getting response from google, continue if successful // end if false
        if ($respData['success']) {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            $userDataset = new UserDataset();
            $userDataset->createUser($username, $password, $firstname, $lastname, 'https://robohash.org/test');

            // Log User In
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $userDataset->getUser($username)->getId();
            header("Location: index.php");
        }
        else
        {
            die("Captcha failed");
        }
    }
}

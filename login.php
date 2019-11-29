<?php
session_start();
require_once ("Models/UserDataset.php");

if (isset($_POST['submit']))
{
    // TODO: Sanitize input against SQL injection
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userData = new UserDataset();
    if ($userData->Login($username, $password))
    {
        // User has successfully Logged in
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
    }
}
header("Location: index.php");
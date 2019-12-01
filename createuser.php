<?php
require_once ("Models/UserDataset.php");
session_start();
if (isset($_POST['submit']))
{
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

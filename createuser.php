<?php
require_once ("Models/UserDataset.php");

if (isset($_POST['submit']))
{
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $userDataset = new UserDataset();
    $userDataset->createUser($username, $password, $firstname, $lastname);
    header("Location: index.php");
}

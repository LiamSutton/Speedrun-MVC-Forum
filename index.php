<?php
session_start();
require_once ("Models/UserDataset.php");
//test
$userData = new UserDataset();
$view = new stdClass();
$view->pageTitle = "Index";
$view->welcomeMessage = "Welcome User!";

if (isset($_SESSION['loggedIn']))
{
    $view->user = $userData->getUserByID($_SESSION['id']);
}

if(isset($_GET['recaptcha']))
{
    $view->error = "ReCaptcha must be completed";
}
require_once ("Views/index.phtml");
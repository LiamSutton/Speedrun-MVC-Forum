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
    $view->user = $userData->getUser($_SESSION['username']);
}
require_once ("Views/index.phtml");
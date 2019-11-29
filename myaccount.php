<?php
session_start();

require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userData = new UserDataset();

$user = $userData->getUser($_SESSION['username']);
$view = new stdClass();
$view->pageTitle = $user->getUsername() . "'s Account";
$view->user = $user;
require_once ("Views/myaccount.phtml");

<?php
session_start();

require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userData = new UserDataset();

// u_id passed through query string
$id = $_GET['id'];

// New Way
$user = $userData->getUserByID($id);

// Old Way
//$user = $userData->getUser($_SESSION['username']);
$view = new stdClass();
$view->pageTitle = $user->getUsername() . "'s Account";
$view->postCount = $user->getPostCount();
$view->replyCount = $user->getReplyCount()  ;
$view->userPosts = $postData->getAllUserPosts($user->getId());

$view->user = $user;
require_once ("Views/myaccount.phtml");

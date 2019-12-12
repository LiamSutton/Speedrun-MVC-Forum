<?php
session_start();

require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userData = new UserDataset();

// u_id passed through query string
$id = $_GET['id'];


$user = $userData->getUserByID($id);

$view = new stdClass();

$view->pageTitle = $user->getUsername() . "'s Account";
$view->postCount = $user->getPostCount();
$view->replyCount = $user->getReplyCount();
$view->userPosts = $postData->getAllUserPosts($id);


$view->user = $user;
require_once("Views/account.phtml");

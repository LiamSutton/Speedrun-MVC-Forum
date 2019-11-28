<?php
session_start();
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

if (isset($_POST['submit']))
{
    $userDataset = new UserDataset();
    $postsDataset = new PostDataset();
    // Get User
    $user = $userDataset->getUser($_SESSION['username']);

    $posterID = $user->getId();
    $title = $_POST['title'];
    $content = $_POST['content'];
    // Commit it to DB
    $postsDataset->createPost($posterID, $title, $content);
    // Redirect
    header("Location: posts.php");
}
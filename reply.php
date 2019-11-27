<?php
session_start();
require_once ("Models/UserDataset.php");
require_once ("Models/PostDataset.php");

if (isset($_POST['submit']))
{
    // TODO: Construct post object maybe?
    $mainId = $_GET['mainid'];
    $id = $_GET['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userDataset = new UserDataset();
    $postDataset = new PostDataset();
    $user = $userDataset->getUser($_SESSION['username']);
    $p_parentID = $_GET['id'];
    $postDataset->createReply($user->getId(), $_POST['title'], $_POST['content'], $p_parentID);
    header("Location: fullpost.php?id=$mainId");
}
<?php
session_start();
require_once ("Models/PostDataset.php");

$postData = new PostDataset();

$userID = $_SESSION['id'];
$postID = $_GET['id'];

$postData->addToWatchlist($userID, $postID);

header("Location: fullpost.php?id=$postID");
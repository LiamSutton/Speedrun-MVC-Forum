<?php
session_start();
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userID = $_SESSION['id'];
$postID = $_GET['id'];

$postData->removeFromWatchlist($userID, $postID);

// TODO: should be able to unsubscribe from post AND Watchlist page, need to sort out redirect
header("Location: fullpost.php?id=$postID");
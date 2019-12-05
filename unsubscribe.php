<?php
session_start();
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userID = $_SESSION['id'];
$postID = $_GET['id'];

$postData->removeFromWatchlist($userID, $postID);

header("Location: watchlist.php?id=$userID");
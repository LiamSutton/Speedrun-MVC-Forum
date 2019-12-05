<?php
session_start();
require_once ("Models/PostDataset.php");

$postData = new PostDataset();

$userID = $_SESSION['id'];
$postID = $_GET['id'];
$from = $_GET['from'];

$postData->addToWatchlist($userID, $postID);

if ($from == "Watchlist")
{
    header("Location: watchlist.php?id=$userID");
}
else
{
    header("Location: fullpost.php?id=$postID");
}
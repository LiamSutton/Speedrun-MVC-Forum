<?php
session_start();
require_once ("Models/WatchlistData.php");

$watchlistData = new WatchlistData();

$userID = $_SESSION['id'];
$postID = $_GET['id'];
$from = $_GET['from'];

$success = $watchlistData->addToWatchlist($userID, $postID);

if (!$success)
{
    header("Location: fullpost.php?id=$postID&failedsub");
}
else
{
    header("Location: fullpost.php?id=$postID&successfullsub");
}

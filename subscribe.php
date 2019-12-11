<?php
session_start();
require_once ("Models/WatchlistData.php");

$watchlistData = new WatchlistData();

$userID = $_SESSION['id'];
$postID = $_GET['id'];
$from = $_GET['from'];

$watchlistData->addToWatchlist($userID, $postID);

if ($from == "Watchlist")
{
    header("Location: watchlist.php?id=$userID");
}
else
{
    header("Location: fullpost.php?id=$postID");
}
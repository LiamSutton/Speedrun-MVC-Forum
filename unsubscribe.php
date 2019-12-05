<?php
session_start();
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userID = $_SESSION['id'];
$postID = $_GET['id'];
$from = $_GET['from'];
$postData->removeFromWatchlist($userID, $postID);

//TODO: maybe users should be able to subscribe / unsubscribe from the basic posts page?

// if the user unsubscribed from the watchlist page, they should be redirected back to it
if ($from == "Watchlist")
{
    header("Location: watchlist.php?id=$userID");
}

// If they unsubscribed from the page containing the post, they should be redirected back to the post
else
{
    header("Location: fullpost.php?id=$postID");
}
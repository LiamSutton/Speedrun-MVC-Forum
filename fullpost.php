<?php
session_start();
require_once ("Models/PostDataset.php");
require_once ("Models/WatchlistData.php");
$postData = new PostDataset();
$watchlistData = new WatchlistData();

$view = new stdClass();
$id = $_GET['id'];
$view->pageTitle = "Post "  .$id;
// TODO: Why is the main post treated differently than replies? they are the same thing.
$view->mainPost = $postData->getPost($id);
$view->replies = $postData->getReplies($id);

if (isset($_SESSION['loggedIn']))
{
    $userID = $_SESSION['id'];
    $view->isOnWatchlist = $watchlistData->isOnWatchlist($userID, $id);
}

require_once ("Views/fullpost.phtml");
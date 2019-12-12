<?php

session_start();
require_once ("Models/WatchlistData.php");

$watchlistData = new WatchlistData();

$userId = $_GET['id'];
$view = new stdClass();
$view->pageTitle = "Watchlist";
$view->userWatchlist = $watchlistData->getUserWatchlist($userId);

if (isset($_GET['successunsub']))
{
    $view->message = "Item removed from your Watchlist";
}
if (isset($_GET['failedunsub']))
{
    $view->error = "Item could not be removed from your Watchlist";
}

require_once ("Views/watchlist.phtml");

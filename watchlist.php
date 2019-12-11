<?php

session_start();
require_once ("Models/WatchlistData.php");

$watchlistData = new WatchlistData();

$userId = $_GET['id'];
$view = new stdClass();
$view->pageTitle = "Watchlist";
$view->userWatchlist = $watchlistData->getUserWatchlist($userId);

require_once ("Views/watchlist.phtml");

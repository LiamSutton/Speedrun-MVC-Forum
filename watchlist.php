<?php

session_start();
require_once ("Models/PostDataset.php");

$postData = new PostDataset();
$userId = $_GET['id'];
$view = new stdClass();
$view->pageTitle = "Watchlist";
$view->userWatchlist = $postData->getWatchlist($userId);

require_once ("Views/watchlist.phtml");

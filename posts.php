<?php
session_start();
require_once ("Models/PostDataset.php");

$view = new stdClass();
$view->pageTitle = "Posts";
$postDataSet = new PostDataset();
$view->dataSet = $postDataSet->getBasicPosts();
$view->categories = $postDataSet->getCategories();

require_once ("Views/posts.phtml");
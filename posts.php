<?php
require_once ("Models/PostDataset.php");

$view = new stdClass();
$view->pageTitle = "Posts";
$postDataSet = new PostDataset();
$view->dataSet = $postDataSet->getBasicPosts();

require_once ("Views/posts.phtml");
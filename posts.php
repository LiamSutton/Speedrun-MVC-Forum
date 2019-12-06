<?php
session_start();
require_once ("Models/PostDataset.php");
require_once ("Models/CategoryData.php");

$view = new stdClass();
$view->pageTitle = "Posts";

$postDataSet = new PostDataset();
$categoryData = new CategoryData();

$view->dataSet = $postDataSet->getBasicPosts();
$view->categoryData = $categoryData->getAllCategories();

require_once ("Views/posts.phtml");
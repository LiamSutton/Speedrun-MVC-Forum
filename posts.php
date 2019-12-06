<?php
session_start();
require_once ("Models/PostDataset.php");
require_once ("Models/CategoryData.php");

$view = new stdClass();
$view->pageTitle = "Posts";

$postDataSet = new PostDataset();
$categoryData = new CategoryData();

$categoryID = $_GET['categoryID'];
$view->posts = $postDataSet->getBasicPosts($categoryID);

// TODO: Maybe a better way of doing this?
$view->categoryName = $categoryData->getCategoryName($categoryID);


require_once ("Views/posts.phtml");
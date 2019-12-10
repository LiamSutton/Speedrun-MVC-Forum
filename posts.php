<?php
session_start();
require_once ("Models/PostDataset.php");
require_once ("Models/CategoryData.php");

$view = new stdClass();
$view->pageTitle = "Posts";

$postDataSet = new PostDataset();
$categoryData = new CategoryData();

$categoryID = $_GET['categoryID'];
$page = $_GET['page'];
$limit = $_GET['limit'];
$view->posts = $postDataSet->getBasicPosts($categoryID, $limit, $page);

// TODO: Maybe a better way of doing this?
$view->categoryName = $categoryData->getCategoryName($categoryID);


require_once ("Views/posts.phtml");
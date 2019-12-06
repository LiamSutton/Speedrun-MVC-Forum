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


require_once ("Views/posts.phtml");
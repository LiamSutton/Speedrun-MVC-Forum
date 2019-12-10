<?php
session_start();
require_once ("Models/PostDataset.php");
require_once ("Models/CategoryData.php");

$view = new stdClass();
$view->pageTitle = "Posts";

$postDataSet = new PostDataset();
$categoryData = new CategoryData();

if (isset($_POST['submit']))
{
    $title = $_POST['title'];
    $limit = $_POST['limit'];
}
else
{
    $limit = $_GET['limit'];
}
$page = $_GET['page'];
$categoryID = $_GET['categoryID'];
$view->posts = $postDataSet->getBasicPosts($categoryID, $limit, $page);

$view->pageCount = $postDataSet->getPageCount($categoryID, $limit);

// TODO: Maybe a better way of doing this?
$view->categoryName = $categoryData->getCategoryName($categoryID);
$view->currentPage = $page;
$view->nextPage = $page+1;
$view->next2 = $view->nextPage+1;
$view->previousPage = $page-1;
$view->previous2 = $view->previousPage-1;
$view->currentCategory = $categoryID;
$view->limit = $limit;

require_once ("Views/posts.phtml");
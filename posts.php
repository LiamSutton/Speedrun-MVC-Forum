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
    $sortBy = $_POST['sort'];
}
else
{
    $title = $_GET['title'];
    $limit = $_GET['limit'];
    $sortBy = $_GET['sort'];
}


$page = $_GET['page'];
$page = $page > 0 ? $page: 1;

$categoryID = $_GET['categoryID'];
$view->posts = $postDataSet->getBasicPosts($categoryID, $limit, $page, $sortBy, $title);

$view->pageCount = $postDataSet->getPageCount($categoryID, $limit, $title);

// TODO: Maybe a better way of doing this?
$view->categoryName = $categoryData->getCategoryName($categoryID);
$view->currentPage = $page;
$view->nextPage = $page+1;
$view->next2 = $view->nextPage+1;
$view->previousPage = $page-1;
$view->previous2 = $view->previousPage-1;
$view->currentCategory = $categoryID;
$view->limit = $limit;
$view->sortBy = $sortBy;
$view->title = $title;

require_once ("Views/posts.phtml");
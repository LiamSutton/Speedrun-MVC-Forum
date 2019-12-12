<?php
session_start();
require_once ("Models/PostDataset.php");
require_once ("Models/CategoryData.php");
require_once ("Models/Pagination.php");

$view = new stdClass();
$view->pageTitle = "Posts";

$postDataSet = new PostDataset();
$categoryData = new CategoryData();

if (isset($_POST['submit']))
{
    $title = $_POST['title'];
    $limit = $_POST['limit'] <= 0 ? 5 : $_POST['limit'];
    $dateOrder = $_POST['date'];
    $commentOrder = $_POST['comment'];
}
else
{
    $title = $_GET['title'];
    $limit = $_GET['limit'] <= 0 ? 5 : $_GET['limit'];
    $dateOrder = $_GET['date'];
    $commentOrder = $_GET['comment'];
}

$categoryID = $_GET['categoryID'];
$page = $_GET['page'];

$view->pageCount = $postDataSet->getPageCount($categoryID, $limit, $title);

$page = $page < $view->pageCount ? $page : $view->pageCount;
$page = $page > 0 ? $page: 1;

$view->availablePages = Pagination::generatePages($view->pageCount, $page, 2);


$view->posts = $postDataSet->getBasicPosts($categoryID, $limit, $page, $dateOrder, $title, $commentOrder);

// TODO: Maybe a better way of doing this?
$view->categoryName = $categoryData->getCategoryName($categoryID);
$view->currentPage = $page;
$view->nextPage = $page+1;
$view->previousPage = $page-1;
$view->currentCategory = $categoryID;
$view->limit = $limit;
$view->dateOrder = $dateOrder;
$view->commentOrder = $commentOrder;
$view->title = $title;

require_once ("Views/posts.phtml");
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
    $limit = $_POST['limit'];
    $dateOrder = $_POST['date'];
    $commentOrder = $_POST['comment'];
}
else
{
    $title = $_GET['title'];
    $limit = $_GET['limit'];
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

$view->distMax = $view->pageCount - $page;
$view->distMin = $page - 1;

$view->renderBelow = $view->distMin;
//echo "<h2>DIST MAX $view->distMax</h2>";
//echo "<h2>DIST MIN $view->distMin</h2>";
// TODO: Maybe a better way of doing this?
$view->categoryName = $categoryData->getCategoryName($categoryID);
$view->currentPage = $page;
$view->nextPage = $page+1;
$view->next2 = $view->nextPage+1;
$view->previousPage = $page-1;
$view->previous2 = $view->previousPage-1;
$view->currentCategory = $categoryID;
$view->limit = $limit;
$view->dateOrder = $dateOrder;
$view->commentOrder = $commentOrder;
$view->title = $title;

require_once ("Views/posts.phtml");
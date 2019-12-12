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
    $title = htmlentities($_POST['title']);
    $limit = htmlentities($_POST['limit'] <= 0 ? 5 : $_POST['limit']);
    $dateOrder = htmlentities($_POST['date']);
    $commentOrder = htmlentities($_POST['comment']);
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

if (isset($_GET['posted']))
{
    $view->message = "Post submitted";
}
if (isset($_GET['recaptcha']))
{
    $view->error = "ReCaptcha must be completed";
}

if (isset($_GET['failed']))
{
    $view->error = "Unable to create post";
}

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
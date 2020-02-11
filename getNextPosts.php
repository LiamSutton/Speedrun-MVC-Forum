<?php
require_once ("Models/PostDataset.php");

$view = new stdClass();

$page = $_REQUEST['page'];
$categoryID = $_REQUEST['categoryID'];
$limit = $_REQUEST['limit'];
$dateOrder = $_REQUEST['date'];
$commentOrder = $_REQUEST['comment'];
$title = $_REQUEST['title'];

$postData = new PostDataset();
$posts = $postData->getBasicPosts($categoryID, $limit, $page, $dateOrder, $title, $commentOrder);

echo json_encode($posts);
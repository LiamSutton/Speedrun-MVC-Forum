<?php
require_once ("Models/PostDataset.php");
$dataset = new PostDataset();

$view = new stdClass();
$id = $_GET['id'];
$view->pageTitle = "Post "  .$id;
$view->mainPost = $dataset->getPost($id);
$view->replies = $dataset->getReplies($id);
require_once ("Views/fullpost.phtml");
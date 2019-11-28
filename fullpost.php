<?php
session_start();
require_once ("Models/PostDataset.php");
$dataset = new PostDataset();

$view = new stdClass();
$id = $_GET['id'];
$view->pageTitle = "Post "  .$id;
// TODO: Why is the main post treated differently than replies? they are the same thing.
$view->mainPost = $dataset->getPost($id);
$view->replies = $dataset->getReplies($id);
require_once ("Views/fullpost.phtml");
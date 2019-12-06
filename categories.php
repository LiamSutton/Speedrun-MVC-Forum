<?php
session_start();
require_once ("Models/CategoryData.php");

$categoryData = new CategoryData();

$view = new stdClass();
$view->pageTitle = "Categories";

$view->categories = $categoryData->getAllCategories();
require_once ("Views/categories.phtml");

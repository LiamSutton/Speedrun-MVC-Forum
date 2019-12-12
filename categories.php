<?php
session_start();
require_once ("Models/CategoryData.php");

$categoryData = new CategoryData();

$view = new stdClass();
$view->pageTitle = "Categories";

$view->categories = $categoryData->getAllCategories();

if (isset($_GET['success']))
{
    $view->message = "Account created, You have been logged in";
}
if (isset($_GET['recaptcha']))
{
    $view->error = "ReCaptcha must be completed";
}
if (isset($_GET['failed']))
{
    $view->error = "Failed to create user";
}
if (isset($_GET['unauthorized']))
{
    $view->error = "You are not authorized to access that location";
}
require_once ("Views/categories.phtml");

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
if (isset($_GET['loginfailed']))
{
    $view->error = "Incorrect login details provided";
}
if (isset($_GET['unauthorized']))
{
    $view->error = "You are not authorized to access that location";
}

if (isset($_GET['loginsuccess']))
{
    $view->message = "You have been logged in succesfully";
}
if (isset($_GET['loggedout']))
{
    $view->message = "You have been successfully logged out";
}
require_once ("Views/categories.phtml");

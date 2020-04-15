<?php
session_start();

require_once ("Models/MessageData.php");

$messageData = new MessageData();

$view = new stdClass();
$view->pageTitle = "Messages";

if (isset($_SESSION['loggedIn']))
{
    $userID = $_SESSION['id'];

}

require_once ("Views/messages.phtml");

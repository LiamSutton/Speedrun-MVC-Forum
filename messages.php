<?php
session_start();

require_once ("Models/MessageData.php");

$messageData = new MessageData();

$view = new stdClass();
$view->pageTitle = "Messages";

if (isset($_SESSION['loggedIn']))
{
    $userID = $_SESSION['id'];

    $view->recievedMessages = $messageData->getRecievedMessages($userID);
    $view->sentMessages = $messageData->getSentMessages($userID);
}

require_once ("Views/messages.phtml");

<?php
session_start();
require_once ("Models/MessageData.php");
$view = new stdClass();
$view->pageTitle = "Conversation";

$other = $_GET['id'];
$view->other = $other;
$user = $_SESSION['id'];

$messageData = new MessageData();
$messageData->openMessages($other, $user);
require_once ("Views/conversation.phtml");

<?php
session_start();
require_once ("Models/MessageData.php");
$view = new stdClass();
$view->pageTitle = "Conversation";

$other = $_GET['id'];
$view->other = $other;
$user = $_SESSION['id'];
// Get all messages between two users

require_once ("views/conversation.phtml");
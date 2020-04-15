<?php
session_start();
require_once ("Models/MessageData.php");

$user = $_SESSION['id'];
$other = $_GET['id'];
$messageData = new MessageData();
$history = $messageData->getConversationHistory($user, $other);

echo json_encode($history, true);
<?php
session_start();
require_once ("Models/MessageData.php");

$id = $_SESSION['id'];
$messageData = new MessageData();
$conversations = $messageData->getConversationList($id);
echo json_encode($conversations, true);


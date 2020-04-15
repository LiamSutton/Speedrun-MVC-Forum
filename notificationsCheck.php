<?php
require_once ("Models/MessageData.php");
session_start();
// Only check for notifications if the user is logged in
if (isset($_SESSION['loggedIn'])) {
    $id = $_SESSION['id'];
    $messageData = new MessageData();
    $count = $messageData->getUnopenedMessages($id);
    echo json_encode($count, true);
}
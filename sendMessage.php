<?php
require_once ("Models/MessageData.php");
session_start();
    $messageData = new MessageData();
    $senderID = $_SESSION['id'];
    $recipientID = htmlentities($_POST['id']);
    $content = htmlentities($_POST['content']);

    $messageData->sendMessage($senderID, $recipientID, $content);


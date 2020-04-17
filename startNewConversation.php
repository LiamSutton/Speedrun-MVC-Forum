<?php
session_start();
require_once ("Models/MessageData.php");
require_once ("Models/UserDataset.php");

//TODO: THIS WHOLE FILE IS CLAPPED (not poggers)
$messageData = new MessageData();
$userData = new UserDataset();

$recipientUsername = $_POST['id'];
$sender = $_SESSION['id'];
$recipient = $userData->getUser($recipientUsername);
$content = $_POST['content'];
$messageData->sendMessage($sender, $recipient->getId(), $content);
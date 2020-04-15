<?php
require_once ("Models/MessageData.php");
session_start();
$messageData = new MessageData();
$id = $_SESSION['id'];
$messageData->markAllAsRead($id);
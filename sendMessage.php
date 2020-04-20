<?php
require_once ("Models/MessageData.php");
require_once ("Models/FileUpload.php");
session_start();
    $messageData = new MessageData();
    $senderID = $_SESSION['id'];
    $recipientID = htmlentities($_POST['id']);
    $content = htmlentities($_POST['content']);

    if (isset($_FILES['img'])) {
        if ($_FILES['img']['error'] == 0)
        {
            // TODO: Check the image is of certain file types {.jpg, .png, .jpeg} ect...
            $messageImg = $_FILES['img']['name'];
            FileUpload::uploadImage("img");
        }
    }
    else
    {
    $messageImg = null;
    }
//
//
    $messageData->sendMessage($senderID, $recipientID, $content, $messageImg);



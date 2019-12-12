<?php

if (isset($_POST['submit']))
{
    $senderID = $_SESSION['id'];
    $recipientID = $_GET['id'];
    $content = htmlentities($_POST['content']);


}

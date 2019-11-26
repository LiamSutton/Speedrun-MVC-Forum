<?php
// Deletes all session data for a user, rendering them not logged in
session_start();
session_destroy();
header("Location: index.php");

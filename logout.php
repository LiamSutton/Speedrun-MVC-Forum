<?php
// Deletes all session data for a user, logging them out
session_start();
session_destroy();
header("Location: categories.php?loggedout");

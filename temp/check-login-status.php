<?php
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION['user_id']);
echo $loggedIn ? "true" : "false";
?>

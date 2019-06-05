<?php
// Start session
session_start();

// Unset session var
$_SESSION = array();

// Destroy session
session_destroy();

// Header refresh to home
header("Location: index.php?page=home");
?>

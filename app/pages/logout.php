<?php
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to a login page or any other page after logout
header("Location: ../../index.php");
exit();
?>
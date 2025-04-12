<?php
session_start();
session_destroy(); // Destroy the session
header("Location: home.html"); // Redirect to the homepage
exit();
?>
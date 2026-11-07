<?php
// Access control - include at the top of every protected page
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>

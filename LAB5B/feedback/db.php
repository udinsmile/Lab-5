<?php
// Database connection - loaded with require (the pages cannot work without it)
$host    = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "course_feedback";

mysqli_report(MYSQLI_REPORT_OFF);
$con = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$con) {
    die("<p style='color:#b00020;font-family:Arial'>Database connection failed: "
        . htmlspecialchars(mysqli_connect_error()) . "</p>");
}
mysqli_set_charset($con, "utf8mb4");
?>

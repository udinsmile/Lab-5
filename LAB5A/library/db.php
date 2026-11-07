<?php
// Database connection - included in every page that needs the database
$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$db_name  = "library";

// Report errors ourselves instead of PHP throwing exceptions
mysqli_report(MYSQLI_REPORT_OFF);

$con = mysqli_connect($host, $db_user, $db_pass, $db_name);

// Clear error message if the connection fails
if (!$con) {
    die("<p style='color:#b00020;font-family:Arial'>Database connection failed: "
        . htmlspecialchars(mysqli_connect_error()) . "</p>");
}

mysqli_set_charset($con, "utf8mb4");
?>

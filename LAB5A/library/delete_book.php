<?php
require("auth.php");
require("db.php");

// Only accept POST (the Delete button in view_books.php, after confirmation)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: view_books.php");
    exit();
}

$id = (int)($_POST["id"] ?? 0);

$stmt = mysqli_prepare($con, "DELETE FROM books WHERE book_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$deleted = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

header("Location: view_books.php?msg=" . ($deleted > 0 ? "deleted" : "notfound"));
exit();
?>

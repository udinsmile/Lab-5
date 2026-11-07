<?php
require("auth.php");
require("db.php");
require("functions.php");

// Small summary for the Home page
$total = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM books"))[0];

$stmt = mysqli_prepare($con, "SELECT COUNT(*) FROM books WHERE added_by = ?");
mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
mysqli_stmt_execute($stmt);
$mine = mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];
mysqli_stmt_close($stmt);

$pageTitle = "Home";
include("header.php");
?>
<h1>Welcome, <?php echo e($_SESSION["username"]); ?>!</h1>
<p>Use the menu above to add, view, search, edit or delete book records.</p>

<div class="stats">
  <div class="stat"><div class="num"><?php echo (int)$total; ?></div>Total books</div>
  <div class="stat"><div class="num"><?php echo (int)$mine; ?></div>Books added by you</div>
</div>

<p>
  <a class="btn" href="add_book.php">+ Add Book</a>
  <a class="btn secondary" href="view_books.php">View Books</a>
</p>
<?php include("footer.php"); ?>

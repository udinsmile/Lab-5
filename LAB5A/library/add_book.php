<?php
require("auth.php");
require("db.php");
require("functions.php");

$book = ["title" => "", "author" => "", "publish_year" => "", "category" => ""];
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $book = book_from_post();
    $errors = validate_book($book, $CATEGORIES);

    if (empty($errors)) {
        // Insert with a prepared statement; added_by = logged-in user
        $stmt = mysqli_prepare($con,
            "INSERT INTO books (title, author, publish_year, category, added_by, date_added)
             VALUES (?, ?, ?, ?, ?, NOW())");
        $year = (int)$book["publish_year"];
        mysqli_stmt_bind_param($stmt, "ssiss",
            $book["title"], $book["author"], $year, $book["category"], $_SESSION["username"]);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Book \"" . $book["title"] . "\" added successfully.";
            $book = ["title" => "", "author" => "", "publish_year" => "", "category" => ""];
        } else {
            $errors["general"] = "Could not save the book: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}

$pageTitle = "Add Book";
$submitLabel = "Add Book";
include("header.php");
?>
<h1>Add New Book</h1>

<?php if ($success) { ?>
  <div class="msg success"><?php echo e($success); ?> <a href="view_books.php">View books</a></div>
<?php } ?>
<?php if (isset($errors["general"])) { ?>
  <div class="msg error"><?php echo e($errors["general"]); ?></div>
<?php } elseif (!empty($errors)) { ?>
  <div class="msg error">Please correct the errors below.</div>
<?php } ?>

<form method="post" action="">
  <?php include("book_form.php"); ?>
</form>
<?php include("footer.php"); ?>

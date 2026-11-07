<?php
require("auth.php");
require("db.php");
require("functions.php");

// Which book? (id from the URL, forced to an integer)
$id = (int)($_GET["id"] ?? 0);

// Load the existing record
$stmt = mysqli_prepare($con, "SELECT * FROM books WHERE book_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$row) {
    header("Location: view_books.php?msg=notfound");
    exit();
}

// Pre-fill the form with the existing data
$book = [
    "title"        => $row["title"],
    "author"       => $row["author"],
    "publish_year" => (string)$row["publish_year"],
    "category"     => $row["category"],
];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $book = book_from_post();
    $errors = validate_book($book, $CATEGORIES);

    if (empty($errors)) {
        $stmt = mysqli_prepare($con,
            "UPDATE books SET title = ?, author = ?, publish_year = ?, category = ? WHERE book_id = ?");
        $year = (int)$book["publish_year"];
        mysqli_stmt_bind_param($stmt, "ssisi",
            $book["title"], $book["author"], $year, $book["category"], $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: view_books.php?msg=updated");
            exit();
        }
        $errors["general"] = "Could not update the book: " . mysqli_error($con);
        mysqli_stmt_close($stmt);
    }
}

$pageTitle = "Edit Book";
$submitLabel = "Update Book";
include("header.php");
?>
<h1>Edit Book</h1>
<p>Added by <strong><?php echo e($row["added_by"]); ?></strong>
   on <?php echo e(date("d/m/Y H:i", strtotime($row["date_added"]))); ?></p>

<?php if (isset($errors["general"])) { ?>
  <div class="msg error"><?php echo e($errors["general"]); ?></div>
<?php } elseif (!empty($errors)) { ?>
  <div class="msg error">Please correct the errors below.</div>
<?php } ?>

<form method="post" action="edit_book.php?id=<?php echo $id; ?>">
  <?php include("book_form.php"); ?>
</form>
<?php include("footer.php"); ?>

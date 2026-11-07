<?php
require("auth.php");
require("db.php");
require("functions.php");

// Total number of books in the library
$total = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM books"))[0];

// Search (partial match on title or author using LIKE)
$q = trim($_GET["q"] ?? "");

if ($q !== "") {
    $stmt = mysqli_prepare($con,
        "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title");
    $like = "%" . $q . "%";
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($con, "SELECT * FROM books ORDER BY title");
}
$found = mysqli_num_rows($result);

// Messages after edit/delete (fixed list, so nothing from the URL is printed)
$messages = [
    "updated" => "Book updated successfully.",
    "deleted" => "Book deleted successfully.",
    "notfound" => "That book could not be found.",
];
$msg = $messages[$_GET["msg"] ?? ""] ?? "";

$pageTitle = "View Books";
include("header.php");
?>
<h1>Book Records</h1>

<?php if ($msg) { ?>
  <div class="msg <?php echo ($_GET["msg"] === "notfound") ? "error" : "success"; ?>"><?php echo e($msg); ?></div>
<?php } ?>

<form method="get" action="view_books.php" class="search-bar">
  <input type="search" name="q" value="<?php echo e($q); ?>" placeholder="Search by title or author">
  <button type="submit" class="btn">Search</button>
  <?php if ($q !== "") { ?><a class="btn secondary" href="view_books.php">Clear</a><?php } ?>
</form>

<p><strong>Total books: <?php echo (int)$total; ?></strong>
<?php if ($q !== "") { ?>
  &nbsp;|&nbsp; <?php echo (int)$found; ?> result(s) for "<?php echo e($q); ?>"
<?php } ?>
</p>

<?php if ($found === 0) { ?>
  <div class="msg info">
    <?php if ($q !== "") { ?>
      No books found matching "<?php echo e($q); ?>".
    <?php } else { ?>
      No books yet. <a href="add_book.php">Add the first book</a>.
    <?php } ?>
  </div>
<?php } else { ?>
<div class="table-wrap">
<table>
  <thead>
    <tr>
      <th>No.</th>
      <th>Title</th>
      <th>Author</th>
      <th>Year</th>
      <th>Category</th>
      <th>Added By</th>
      <th>Date Added</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?php echo $no++; ?></td>
      <td><?php echo e($row["title"]); ?></td>
      <td><?php echo e($row["author"]); ?></td>
      <td><?php echo e($row["publish_year"]); ?></td>
      <td><?php echo e($row["category"]); ?></td>
      <td><?php echo e($row["added_by"]); ?></td>
      <td><?php echo e(date("d/m/Y", strtotime($row["date_added"]))); ?></td>
      <td class="actions">
        <a class="btn small" href="edit_book.php?id=<?php echo (int)$row["book_id"]; ?>">Edit</a>
        <!-- Delete uses POST and asks for confirmation first -->
        <form method="post" action="delete_book.php"
              onsubmit="return confirm('Delete the book &quot;<?php echo e(addslashes($row["title"])); ?>&quot;?');">
          <input type="hidden" name="id" value="<?php echo (int)$row["book_id"]; ?>">
          <button type="submit" class="btn danger">Delete</button>
        </form>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>
<?php } ?>
<?php include("footer.php"); ?>

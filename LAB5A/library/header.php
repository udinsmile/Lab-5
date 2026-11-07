<?php
// Page layout: top of every page + navigation menu
// Set $pageTitle (and optionally $narrow = true for small forms) before including this file.
$pageTitle = $pageTitle ?? "Mini Library";
$narrow = $narrow ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($pageTitle); ?> - Mini Library</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
  <div class="brand">📚 Mini Library</div>
  <nav>
    <?php if (isset($_SESSION["username"])) { ?>
      <a href="index.php">Home</a>
      <a href="add_book.php">Add Book</a>
      <a href="view_books.php">View Books</a>
      <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION["username"]); ?>)</a>
    <?php } else { ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php } ?>
  </nav>
</header>
<main class="container<?php echo $narrow ? " narrow" : ""; ?>">

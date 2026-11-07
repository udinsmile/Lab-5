<?php
// Layout: navigation menu (loaded with include)
$current = basename($_SERVER["PHP_SELF"]);
?>
<nav class="menu">
  <a href="feedback.php" class="<?php echo $current === "feedback.php" ? "active" : ""; ?>">Feedback Form</a>
  <a href="summary.php" class="<?php echo $current === "summary.php" ? "active" : ""; ?>">Feedback Summary</a>
</nav>
<main class="container">

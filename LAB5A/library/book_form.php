<?php
// Shared form fields for Add Book and Edit Book.
// Expects: $book (field values), $errors (field => message), $CATEGORIES, $submitLabel
?>
<div class="form-row">
  <label for="title">Title</label>
  <input type="text" id="title" name="title" value="<?php echo e($book["title"]); ?>"
         class="<?php echo isset($errors["title"]) ? "invalid" : ""; ?>" required>
  <?php if (isset($errors["title"])) echo '<div class="field-error">' . e($errors["title"]) . '</div>'; ?>
</div>
<div class="form-row">
  <label for="author">Author</label>
  <input type="text" id="author" name="author" value="<?php echo e($book["author"]); ?>"
         class="<?php echo isset($errors["author"]) ? "invalid" : ""; ?>" required>
  <?php if (isset($errors["author"])) echo '<div class="field-error">' . e($errors["author"]) . '</div>'; ?>
</div>
<div class="form-row">
  <label for="publish_year">Publish Year</label>
  <input type="text" id="publish_year" name="publish_year" value="<?php echo e($book["publish_year"]); ?>"
         maxlength="4" placeholder="e.g. 2021"
         class="<?php echo isset($errors["publish_year"]) ? "invalid" : ""; ?>" required>
  <?php if (isset($errors["publish_year"])) echo '<div class="field-error">' . e($errors["publish_year"]) . '</div>'; ?>
</div>
<div class="form-row">
  <label for="category">Category</label>
  <select id="category" name="category" class="<?php echo isset($errors["category"]) ? "invalid" : ""; ?>" required>
    <option value="">-- Choose a category --</option>
    <?php foreach ($CATEGORIES as $cat) { ?>
      <option value="<?php echo e($cat); ?>" <?php echo $book["category"] === $cat ? "selected" : ""; ?>>
        <?php echo e($cat); ?>
      </option>
    <?php } ?>
  </select>
  <?php if (isset($errors["category"])) echo '<div class="field-error">' . e($errors["category"]) . '</div>'; ?>
</div>
<button type="submit" class="btn"><?php echo e($submitLabel); ?></button>
<a class="btn secondary" href="view_books.php">Cancel</a>

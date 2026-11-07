<?php
require "db.php";               // require: page cannot work without the database
require_once "functions.php";   // require_once: functions must be loaded exactly once

$courses = get_courses();
$ratingFields = [
    "content"    => "Course Content",
    "teaching"   => "Teaching",
    "assessment" => "Assessment",
];

// Values shown in the form (kept after an error = sticky form)
$name = $matric = $course = $comment = "";
$ratings = ["content" => "", "teaching" => "", "assessment" => ""];

// One list of error messages per field
$errors = [
    "name" => [], "matric" => [], "course" => [],
    "content" => [], "teaching" => [], "assessment" => [],
    "comment" => [], "general" => [],
];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Name (required)
    $name = clean_input($_POST["name"] ?? "");
    if ($name === "") {
        add_error($errors["name"], "Name is required.");
    } elseif (!preg_match("/^[\p{L} .'@-]{2,100}$/u", $name)) {
        add_error($errors["name"], "Name may only contain letters, spaces and . ' @ -");
    }

    // Matric number (required, 2 letters + 6 digits)
    $matric = strtoupper(clean_input($_POST["matric"] ?? ""));
    if ($matric === "") {
        add_error($errors["matric"], "Matric number is required.");
    } elseif (!preg_match('/^[A-Z]{2}[0-9]{6}$/', $matric)) {
        add_error($errors["matric"], "Matric number must be 2 letters followed by 6 digits (e.g. AI230123).");
    }

    // Course (required, must be one of the drop-down options)
    $course = clean_input($_POST["course"] ?? "");
    if ($course === "") {
        add_error($errors["course"], "Please choose a course.");
    } elseif (!in_array($course, $courses, true)) {
        add_error($errors["course"], "Please choose a valid course.");
    }

    // Three ratings (required, 1 to 5)
    foreach ($ratingFields as $key => $label) {
        $ratings[$key] = clean_input($_POST[$key] ?? "");
        if ($ratings[$key] === "") {
            add_error($errors[$key], "Please rate the " . strtolower($label) . ".");
        } elseif (!in_array($ratings[$key], ["1", "2", "3", "4", "5"], true)) {
            add_error($errors[$key], "Rating must be between 1 and 5.");
        }
    }

    // Comment (optional, max 500 characters)
    $comment = clean_input($_POST["comment"] ?? "");
    if (strlen($comment) > 500) {
        add_error($errors["comment"], "Comment cannot be longer than 500 characters.");
    }

    // Count all error messages
    $errorCount = 0;
    foreach ($errors as $list) {
        $errorCount += count($list);
    }

    // Save to MySQL with a prepared statement
    if ($errorCount === 0) {
        $stmt = mysqli_prepare($con,
            "INSERT INTO feedback
               (student_name, matric_no, course_code, rating_content, rating_teaching, rating_assessment, comment)
             VALUES (?, ?, ?, ?, ?, ?, ?)");
        $rc = (int)$ratings["content"];
        $rt = (int)$ratings["teaching"];
        $ra = (int)$ratings["assessment"];
        $commentOrNull = ($comment === "") ? null : $comment;
        mysqli_stmt_bind_param($stmt, "sssiiis", $name, $matric, $course, $rc, $rt, $ra, $commentOrNull);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Thank you, " . $name . "! Your feedback for " . $course . " has been saved.";
            // Clear the form for the next student
            $name = $matric = $course = $comment = "";
            $ratings = ["content" => "", "teaching" => "", "assessment" => ""];
        } else {
            add_error($errors["general"], "Could not save your feedback. Please try again.");
        }
        mysqli_stmt_close($stmt);
    }
}

$pageTitle = "Feedback Form";
include "header.php";
include "menu.php";
?>
<h2>Course Feedback Form</h2>
<p class="hint">Fields marked <span class="req">*</span> are required.</p>

<?php if ($success !== "") { ?>
  <div class="msg success"><?php echo e($success); ?>
    <a href="summary.php?course=<?php echo urlencode($courses[0]); ?>">View summary</a></div>
<?php } ?>
<?php if (!empty($errors["general"])) { ?>
  <div class="msg error"><?php echo field_errors($errors["general"]); ?></div>
<?php } elseif (isset($errorCount) && $errorCount > 0) { ?>
  <div class="msg error">Please correct the highlighted fields below.</div>
<?php } ?>

<!-- Self-referencing form: submits to this same page, PHP_SELF escaped -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>

  <div class="field">
    <label for="name">Name <span class="req">*</span></label>
    <input type="text" id="name" name="name" value="<?php echo e($name); ?>"
           class="<?php echo $errors["name"] ? "invalid" : ""; ?>">
    <?php echo field_errors($errors["name"]); ?>
  </div>

  <div class="field">
    <label for="matric">Matric Number <span class="req">*</span></label>
    <input type="text" id="matric" name="matric" value="<?php echo e($matric); ?>"
           maxlength="8" placeholder="e.g. AI230123"
           class="<?php echo $errors["matric"] ? "invalid" : ""; ?>">
    <?php echo field_errors($errors["matric"]); ?>
  </div>

  <div class="field">
    <label for="course">Course Code <span class="req">*</span></label>
    <select id="course" name="course" class="<?php echo $errors["course"] ? "invalid" : ""; ?>">
      <option value="">-- Select a course --</option>
      <?php foreach ($courses as $c) { ?>
        <option value="<?php echo e($c); ?>" <?php echo $course === $c ? "selected" : ""; ?>><?php echo e($c); ?></option>
      <?php } ?>
    </select>
    <?php echo field_errors($errors["course"]); ?>
  </div>

  <fieldset class="ratings">
    <legend>Ratings (1 = Very Poor, 5 = Excellent)</legend>
    <?php foreach ($ratingFields as $key => $label) { ?>
      <div class="field rating-row">
        <span class="rating-label"><?php echo e($label); ?> <span class="req">*</span></span>
        <span class="rating-options">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
            <label class="radio">
              <input type="radio" name="<?php echo $key; ?>" value="<?php echo $i; ?>"
                     <?php echo $ratings[$key] === (string)$i ? "checked" : ""; ?>> <?php echo $i; ?>
            </label>
          <?php } ?>
        </span>
        <?php echo field_errors($errors[$key]); ?>
      </div>
    <?php } ?>
  </fieldset>

  <div class="field">
    <label for="comment">Comment (optional)</label>
    <textarea id="comment" name="comment" rows="4" maxlength="500"
              class="<?php echo $errors["comment"] ? "invalid" : ""; ?>"><?php echo e($comment); ?></textarea>
    <?php echo field_errors($errors["comment"]); ?>
  </div>

  <button type="submit" name="submit" class="btn">Submit Feedback</button>
</form>
<?php include "footer.php"; ?>

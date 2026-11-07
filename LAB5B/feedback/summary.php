<?php
require "db.php";               // require: page cannot work without the database
require_once "functions.php";

$courses = get_courses();

// Course code from the URL, e.g. summary.php?course=BIC21203
$course = strtoupper(trim($_GET["course"] ?? ""));
$notice = "";
if ($course !== "" && !in_array($course, $courses, true)) {
    $notice = "Unknown course code \"" . $course . "\". Showing all courses.";
    $course = "";
}

// Get the feedback (prepared statement when filtering)
if ($course !== "") {
    $stmt = mysqli_prepare($con, "SELECT * FROM feedback WHERE course_code = ? ORDER BY submitted_at DESC");
    mysqli_stmt_bind_param($stmt, "s", $course);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($con, "SELECT * FROM feedback ORDER BY course_code, submitted_at DESC");
}

// Collect the rows and the ratings for the averages
$rows = [];
$content = $teaching = $assessment = [];
$perCourse = [];   // ratings grouped by course (for the "all courses" view)
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
    $content[]    = (int)$r["rating_content"];
    $teaching[]   = (int)$r["rating_teaching"];
    $assessment[] = (int)$r["rating_assessment"];
    $perCourse[$r["course_code"]][] = (int)$r["rating_content"];
    $perCourse[$r["course_code"]][] = (int)$r["rating_teaching"];
    $perCourse[$r["course_code"]][] = (int)$r["rating_assessment"];
}

// Averages and labels using the user-defined functions
$avgContent    = calc_average($content);
$avgTeaching   = calc_average($teaching);
$avgAssessment = calc_average($assessment);
$avgOverall    = calc_average(array_merge($content, $teaching, $assessment));
$overallLabel  = rating_label($avgOverall);

$pageTitle = "Feedback Summary";
include "header.php";
include "menu.php";
?>
<h2>Feedback Summary<?php echo $course !== "" ? " - " . e($course) : " - All Courses"; ?></h2>

<?php if ($notice !== "") { ?>
  <div class="msg error"><?php echo e($notice); ?></div>
<?php } ?>

<!-- Filter by course: sends ?course=... with the GET method -->
<form method="get" action="summary.php" class="filter">
  <label for="course">Course:</label>
  <select id="course" name="course">
    <option value="">All courses</option>
    <?php foreach ($courses as $c) { ?>
      <option value="<?php echo e($c); ?>" <?php echo $course === $c ? "selected" : ""; ?>><?php echo e($c); ?></option>
    <?php } ?>
  </select>
  <button type="submit" class="btn">Show</button>
</form>

<?php if (count($rows) === 0) { ?>
  <div class="msg info">No feedback has been submitted<?php echo $course !== "" ? " for " . e($course) : ""; ?> yet.
    <a href="feedback.php">Give feedback</a></div>
<?php } else { ?>

  <!-- Averages for each rating + overall label -->
  <div class="cards">
    <div class="card">
      <div class="card-title">Course Content</div>
      <div class="card-num"><?php echo number_format($avgContent, 2); ?></div>
      <div class="badge <?php echo strtolower(rating_label($avgContent)); ?>"><?php echo rating_label($avgContent); ?></div>
    </div>
    <div class="card">
      <div class="card-title">Teaching</div>
      <div class="card-num"><?php echo number_format($avgTeaching, 2); ?></div>
      <div class="badge <?php echo strtolower(rating_label($avgTeaching)); ?>"><?php echo rating_label($avgTeaching); ?></div>
    </div>
    <div class="card">
      <div class="card-title">Assessment</div>
      <div class="card-num"><?php echo number_format($avgAssessment, 2); ?></div>
      <div class="badge <?php echo strtolower(rating_label($avgAssessment)); ?>"><?php echo rating_label($avgAssessment); ?></div>
    </div>
    <div class="card overall">
      <div class="card-title">Overall</div>
      <div class="card-num"><?php echo number_format($avgOverall, 2); ?></div>
      <div class="badge <?php echo strtolower($overallLabel); ?>"><?php echo $overallLabel; ?></div>
    </div>
  </div>
  <p class="hint">Based on <?php echo count($rows); ?> response(s).</p>

  <?php if ($course === "") { ?>
    <!-- All courses: one line per course -->
    <h3>By Course</h3>
    <table>
      <thead><tr><th>Course</th><th>Responses</th><th>Overall Average</th><th>Label</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($perCourse as $code => $list) { $avg = calc_average($list); ?>
        <tr>
          <td><?php echo e($code); ?></td>
          <td><?php echo count($list) / 3; ?></td>
          <td><?php echo number_format($avg, 2); ?></td>
          <td><span class="badge <?php echo strtolower(rating_label($avg)); ?>"><?php echo rating_label($avg); ?></span></td>
          <td><a href="summary.php?course=<?php echo urlencode($code); ?>">View</a></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  <?php } ?>

  <h3>All Responses</h3>
  <div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>No.</th><th>Name</th><th>Matric No.</th><th>Course</th>
        <th>Content</th><th>Teaching</th><th>Assessment</th><th>Comment</th><th>Date</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 1; foreach ($rows as $r) { ?>
      <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo e($r["student_name"]); ?></td>
        <td><?php echo e($r["matric_no"]); ?></td>
        <td><?php echo e($r["course_code"]); ?></td>
        <td class="num"><?php echo (int)$r["rating_content"]; ?></td>
        <td class="num"><?php echo (int)$r["rating_teaching"]; ?></td>
        <td class="num"><?php echo (int)$r["rating_assessment"]; ?></td>
        <td><?php echo $r["comment"] !== null ? e($r["comment"]) : "<span class='muted'>-</span>"; ?></td>
        <td><?php echo e(date("d/m/Y", strtotime($r["submitted_at"]))); ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
  </div>
<?php } ?>
<?php include "footer.php"; ?>

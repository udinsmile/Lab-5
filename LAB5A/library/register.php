<?php
session_start();
require("db.php");
require("functions.php");

// Already logged in? Go to Home
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$errors = [];
$success = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm"] ?? "";

    // Validation
    if ($username === "") {
        $errors["username"] = "Username is required.";
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
        $errors["username"] = "Username: 3-50 letters, numbers or underscore only.";
    }
    if (strlen($password) < 6) {
        $errors["password"] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm) {
        $errors["confirm"] = "Passwords do not match.";
    }

    // Is the username already taken? (prepared statement)
    if (empty($errors)) {
        $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors["username"] = "This username is already taken.";
        }
        mysqli_stmt_close($stmt);
    }

    // Save the user with a hashed password
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($con, "INSERT INTO users (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $username, $hash);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Registration successful. You can now log in.";
            $username = "";
        } else {
            $errors["general"] = "Registration failed. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

$pageTitle = "Register";
$narrow = true;
include("header.php");
?>
<h1>Staff Registration</h1>

<?php if ($success) { ?>
  <div class="msg success"><?php echo e($success); ?> <a href="login.php">Login here</a></div>
<?php } ?>
<?php if (isset($errors["general"])) { ?>
  <div class="msg error"><?php echo e($errors["general"]); ?></div>
<?php } ?>

<form method="post" action="">
  <div class="form-row">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?php echo e($username); ?>"
           class="<?php echo isset($errors["username"]) ? "invalid" : ""; ?>" required>
    <?php if (isset($errors["username"])) echo '<div class="field-error">' . e($errors["username"]) . '</div>'; ?>
  </div>
  <div class="form-row">
    <label for="password">Password</label>
    <input type="password" id="password" name="password"
           class="<?php echo isset($errors["password"]) ? "invalid" : ""; ?>" required>
    <?php if (isset($errors["password"])) echo '<div class="field-error">' . e($errors["password"]) . '</div>'; ?>
  </div>
  <div class="form-row">
    <label for="confirm">Confirm Password</label>
    <input type="password" id="confirm" name="confirm"
           class="<?php echo isset($errors["confirm"]) ? "invalid" : ""; ?>" required>
    <?php if (isset($errors["confirm"])) echo '<div class="field-error">' . e($errors["confirm"]) . '</div>'; ?>
  </div>
  <button type="submit" class="btn">Register</button>
</form>
<p>Already registered? <a href="login.php">Login here</a></p>
<?php include("footer.php"); ?>

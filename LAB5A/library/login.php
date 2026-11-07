<?php
session_start();
require("db.php");
require("functions.php");

// Already logged in? Go to Home
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Please enter your username and password.";
    } else {
        // Find the user by username only (prepared statement)
        $stmt = mysqli_prepare($con, "SELECT username, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Compare the typed password with the stored hash
        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);           // new session id after login
            $_SESSION["username"] = $user["username"];
            header("Location: index.php");
            exit();
        } else {
            $error = "Username or password is incorrect.";
        }
    }
}

$pageTitle = "Login";
$narrow = true;
include("header.php");
?>
<h1>Staff Login</h1>

<?php if ($error) { ?>
  <div class="msg error"><?php echo e($error); ?></div>
<?php } ?>

<form method="post" action="">
  <div class="form-row">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?php echo e($username); ?>" required>
  </div>
  <div class="form-row">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
  </div>
  <button type="submit" class="btn">Login</button>
</form>
<p>Not registered yet? <a href="register.php">Register here</a></p>
<?php include("footer.php"); ?>

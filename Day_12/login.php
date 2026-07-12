<?php
session_start();
require "db.php";

// Already logged in? Skip straight to the dashboard.
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $user = mysqli_stmt_get_result($stmt)->fetch_assoc();
    mysqli_stmt_close($stmt);

    // password_verify() checks the plain-text input against the bcrypt hash
    // stored in the DB - never store or compare plain-text passwords.
    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true); // fresh session id on login, avoids session fixation
        $_SESSION['user_id'] = $user["id"];
        $_SESSION['username'] = $user["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

$page_title = "Login";
require "includes/header.php";
?>

<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm fade-in-card mt-5">
      <div class="card-body p-4">
        <h1 class="h4 mb-4 text-center"><i class="bi bi-shield-lock-fill text-primary"></i> NexaLearn Login</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person"></i> Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-key"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Log In
            </button>
        </form>

        <p class="text-muted small mt-3 mb-0">Default demo login: <code>admin</code> / <code>admin123</code></p>
      </div>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; ?>

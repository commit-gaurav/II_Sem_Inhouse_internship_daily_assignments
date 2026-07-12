<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Already logged in? Skip straight to the dashboard.
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, name, password, profile_picture FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            // Credentials good — start the session.
            session_regenerate_id(true);
            $_SESSION['user_id']     = $user['id'];
            $_SESSION['user_name']   = $user['name'];
            $_SESSION['user_avatar'] = $user['profile_picture'];

            // Update last_login timestamp.
            $update = $conn->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
            $update->bind_param('i', $user['id']);
            $update->execute();
            $update->close();

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login · NexaLearn</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body {
    min-height: 100vh; display:flex; align-items:center; justify-content:center;
    background: linear-gradient(135deg,#0d1b2a,#1b3a5c);
  }
  .login-card { width:100%; max-width:400px; border:none; border-radius:1rem; box-shadow:0 15px 40px rgba(0,0,0,.25); }
  .login-icon { width:60px; height:60px; border-radius:50%; background:#0d6efd; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; }
</style>
</head>
<body>
  <div class="card login-card p-4">
    <div class="login-icon"><i class="bi bi-mortarboard-fill text-white fs-4"></i></div>
    <h4 class="text-center fw-bold mb-1">NexaLearn</h4>
    <p class="text-center text-muted mb-4">Student Management System</p>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <div class="mb-3">
        <label class="form-label">Email address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" class="form-control" name="email" required
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@example.com">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" class="form-control" name="password" required placeholder="••••••••">
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div></div>
        <a href="forgot_password.php" class="small">Forgot password?</a>
      </div>
      <button type="submit" class="btn btn-primary w-100">Sign In</button>
    </form>
    <p class="text-center text-muted small mt-3 mb-0">
      Demo login: admin@nexalearn.com / Admin@123<br>
      (run reset_admin_password.php once to activate it)
    </p>
  </div>
</body>
</html>

<?php
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // UI only — no email sending logic required by the assignment.
    $submitted = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password · NexaLearn</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body { min-height:100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg,#0d1b2a,#1b3a5c); }
  .card-box { width:100%; max-width:420px; border:none; border-radius:1rem; box-shadow:0 15px 40px rgba(0,0,0,.25); }
  .icon-circle { width:60px; height:60px; border-radius:50%; background:#0d6efd; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; }
</style>
</head>
<body>
  <div class="card card-box p-4">
    <div class="icon-circle"><i class="bi bi-key-fill text-white fs-4"></i></div>
    <h4 class="text-center fw-bold mb-1">Forgot Password</h4>

    <?php if (!$submitted): ?>
      <p class="text-center text-muted mb-4">Enter your account email and we'll send you a reset link.</p>
      <form method="POST" action="forgot_password.php">
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" name="email" required placeholder="you@example.com">
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
      </form>
    <?php else: ?>
      <div class="text-center py-3">
        <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
        <p class="mt-3 mb-0">If an account exists for that email, a password reset link has been sent.</p>
      </div>
    <?php endif; ?>

    <p class="text-center small mt-3 mb-0"><a href="login.php"><i class="bi bi-arrow-left me-1"></i>Back to Login</a></p>
  </div>
</body>
</html>

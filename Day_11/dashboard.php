<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

// Fetch fresh user info, including last_login.
$stmt = $conn->prepare('SELECT name, email, last_login, profile_picture FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$lastLogin = $user['last_login'] ? date('d M Y, h:i A', strtotime($user['last_login'])) : 'This is your first login';

$pageTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>

<h4 class="fw-bold mb-1">Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h4>
<p class="text-muted mb-4">Here's what's happening with your account.</p>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <i class="bi bi-clock-history text-primary fs-3"></i>
        <h6 class="mt-2 mb-1 text-muted">Last Login</h6>
        <p class="fw-semibold mb-0"><?= htmlspecialchars($lastLogin) ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <i class="bi bi-envelope-check text-success fs-3"></i>
        <h6 class="mt-2 mb-1 text-muted">Account Email</h6>
        <p class="fw-semibold mb-0"><?= htmlspecialchars($user['email']) ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <i class="bi bi-shield-check text-warning fs-3"></i>
        <h6 class="mt-2 mb-1 text-muted">Session Status</h6>
        <p class="fw-semibold mb-0 text-success">Active &amp; Secure</p>
      </div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm mt-4">
  <div class="card-body">
    <h6 class="fw-bold">Quick Links</h6>
    <a href="profile.php" class="btn btn-outline-primary btn-sm me-2 mt-2"><i class="bi bi-person-circle me-1"></i>View Profile</a>
    <a href="change_password.php" class="btn btn-outline-secondary btn-sm mt-2"><i class="bi bi-shield-lock me-1"></i>Change Password</a>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

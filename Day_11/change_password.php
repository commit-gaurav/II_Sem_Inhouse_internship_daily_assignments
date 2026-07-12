<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($current === '' || $new === '' || $confirm === '') {
        $error = 'Please fill in all fields.';
    } elseif ($new !== $confirm) {
        $error = 'New password and confirmation do not match.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } else {
        $stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || !password_verify($current, $row['password'])) {
            $error = 'Current password is incorrect.';
        } else {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $update->bind_param('si', $newHash, $_SESSION['user_id']);
            $update->execute();
            $update->close();
            $success = 'Your password has been updated successfully.';
        }
    }
}

$pageTitle = 'Change Password';
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i>Change Password</h5>

        <?php if ($error): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <form method="POST" action="change_password.php">
          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-control" name="current_password" required>
          </div>
          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" required minlength="8">
            <div class="form-text">At least 8 characters.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" required minlength="8">
          </div>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

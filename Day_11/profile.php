<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$error = '';
$success = '';

// Handle avatar upload.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed. Please try again.';
    } else {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = mime_content_type($file['tmp_name']);

        if (!isset($allowed[$mime])) {
            $error = 'Only JPG, PNG, or WEBP images are allowed.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $error = 'Image must be smaller than 2MB.';
        } else {
            $ext = $allowed[$mime];
            $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            $destination = __DIR__ . '/uploads/avatars/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $stmt = $conn->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
                $stmt->bind_param('si', $filename, $_SESSION['user_id']);
                $stmt->execute();
                $stmt->close();

                $_SESSION['user_avatar'] = $filename;
                $success = 'Profile picture updated.';
            } else {
                $error = 'Could not save the uploaded file.';
            }
        }
    }
}

// Fetch current user info.
$stmt = $conn->prepare('SELECT name, email, profile_picture, created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$avatarSrc = !empty($user['profile_picture'])
    ? 'uploads/avatars/' . htmlspecialchars($user['profile_picture'])
    : 'https://ui-avatars.com/api/?background=0d6efd&color=fff&size=160&name=' . urlencode($user['name']);

$pageTitle = 'Profile';
require __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 text-center">
        <?php if ($error): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <img src="<?= $avatarSrc ?>" class="rounded-circle mb-3" width="140" height="140" style="object-fit:cover;" alt="Profile picture">

        <h5 class="fw-bold mb-0"><?= htmlspecialchars($user['name']) ?></h5>
        <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
        <p class="text-muted small">Member since <?= date('d M Y', strtotime($user['created_at'])) ?></p>

        <hr>

        <form method="POST" action="profile.php" enctype="multipart/form-data" class="text-start">
          <label class="form-label fw-semibold">Update Profile Picture</label>
          <input type="file" name="avatar" class="form-control mb-3" accept="image/png, image/jpeg, image/webp" required>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload me-1"></i>Upload</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

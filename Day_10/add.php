<?php
require 'config/db.php';
$pageTitle = 'Add Student';

$errors = [];
$old = ['name' => '', 'email' => '', 'branch' => '', 'cgpa' => '', 'status' => 'Active'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']   = trim($_POST['name'] ?? '');
    $old['email']  = trim($_POST['email'] ?? '');
    $old['branch'] = trim($_POST['branch'] ?? '');
    $old['cgpa']   = trim($_POST['cgpa'] ?? '');
    $old['status'] = trim($_POST['status'] ?? 'Active');

    // ---------------- Server-side validation ----------------
    if ($old['name'] === '') {
        $errors['name'] = 'Name is required.';
    }
    if ($old['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if ($old['branch'] === '') {
        $errors['branch'] = 'Branch is required.';
    }
    if ($old['cgpa'] === '' || !is_numeric($old['cgpa']) || $old['cgpa'] < 0 || $old['cgpa'] > 10) {
        $errors['cgpa'] = 'CGPA must be a number between 0 and 10.';
    }
    if (!in_array($old['status'], ['Active', 'Inactive'], true)) {
        $errors['status'] = 'Invalid status.';
    }

    // ---------------- Duplicate email check ----------------
    if (empty($errors['email'])) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = :email");
        $check->execute([':email' => $old['email']]);
        if ($check->fetchColumn() > 0) {
            $errors['email'] = 'A student with this email already exists.';
        }
    }

    // ---------------- Photo upload ----------------
    $photoFilename = null;
    if (!empty($_FILES['photo']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize      = 2 * 1024 * 1024; // 2MB

        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'Error uploading photo.';
        } elseif (!in_array($_FILES['photo']['type'], $allowedTypes, true)) {
            $errors['photo'] = 'Only JPG, PNG, or WEBP images are allowed.';
        } elseif ($_FILES['photo']['size'] > $maxSize) {
            $errors['photo'] = 'Photo must be smaller than 2MB.';
        } else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photoFilename = 'stu_' . uniqid() . '.' . strtolower($ext);
            $destination   = __DIR__ . '/uploads/' . $photoFilename;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $errors['photo'] = 'Failed to save uploaded photo.';
                $photoFilename = null;
            }
        }
    }

    // ---------------- Insert into DB ----------------
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO students (name, email, branch, cgpa, status, photo)
             VALUES (:name, :email, :branch, :cgpa, :status, :photo)"
        );
        $stmt->execute([
            ':name'   => $old['name'],
            ':email'  => $old['email'],
            ':branch' => $old['branch'],
            ':cgpa'   => $old['cgpa'],
            ':status' => $old['status'],
            ':photo'  => $photoFilename,
        ]);

        header('Location: index.php?success=added');
        exit;
    }
}

include 'includes/header.php';
?>

<div class="card" style="max-width: 640px; margin: 0 auto;">
  <div class="card-body p-4">
    <h4 class="mb-4"><i class="bi bi-person-plus-fill text-primary"></i> Add New Student</h4>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill"></i> Please fix the errors below.
      </div>
    <?php endif; ?>

    <form method="POST" action="add.php" enctype="multipart/form-data" novalidate>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
               value="<?php echo htmlspecialchars($old['name']); ?>" required>
        <?php if (isset($errors['name'])): ?><div class="invalid-feedback"><?php echo $errors['name']; ?></div><?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
               value="<?php echo htmlspecialchars($old['email']); ?>" required>
        <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?php echo $errors['email']; ?></div><?php endif; ?>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Branch</label>
          <select name="branch" class="form-select <?php echo isset($errors['branch']) ? 'is-invalid' : ''; ?>" required>
            <option value="">Select branch</option>
            <?php foreach (['CSE', 'ECE', 'ME', 'CE', 'EE', 'IT'] as $b): ?>
              <option value="<?php echo $b; ?>" <?php echo $old['branch'] === $b ? 'selected' : ''; ?>><?php echo $b; ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($errors['branch'])): ?><div class="invalid-feedback"><?php echo $errors['branch']; ?></div><?php endif; ?>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">CGPA (0-10)</label>
          <input type="number" step="0.01" min="0" max="10" name="cgpa"
                 class="form-control <?php echo isset($errors['cgpa']) ? 'is-invalid' : ''; ?>"
                 value="<?php echo htmlspecialchars($old['cgpa']); ?>" required>
          <?php if (isset($errors['cgpa'])): ?><div class="invalid-feedback"><?php echo $errors['cgpa']; ?></div><?php endif; ?>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Active"   <?php echo $old['status'] === 'Active'   ? 'selected' : ''; ?>>Active</option>
          <option value="Inactive" <?php echo $old['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="form-label">Profile Photo (optional)</label>
        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
               class="form-control <?php echo isset($errors['photo']) ? 'is-invalid' : ''; ?>">
        <div class="form-text">JPG, PNG or WEBP, max 2MB.</div>
        <?php if (isset($errors['photo'])): ?><div class="invalid-feedback"><?php echo $errors['photo']; ?></div><?php endif; ?>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle-fill"></i> Save Student
        </button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

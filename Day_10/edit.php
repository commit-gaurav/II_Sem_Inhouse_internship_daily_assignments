<?php
require 'config/db.php';
$pageTitle = 'Edit Student';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch existing record
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
$stmt->execute([':id' => $id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: index.php');
    exit;
}

$errors = [];
$old = [
    'name'   => $student['name'],
    'email'  => $student['email'],
    'branch' => $student['branch'],
    'cgpa'   => $student['cgpa'],
    'status' => $student['status'],
];

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

    // ---------------- Duplicate email check (excluding this record) ----------------
    if (empty($errors['email'])) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = :email AND id != :id");
        $check->execute([':email' => $old['email'], ':id' => $id]);
        if ($check->fetchColumn() > 0) {
            $errors['email'] = 'Another student already uses this email.';
        }
    }

    // ---------------- Optional new photo ----------------
    $photoFilename = $student['photo']; // keep existing by default
    if (!empty($_FILES['photo']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize      = 2 * 1024 * 1024;

        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'Error uploading photo.';
        } elseif (!in_array($_FILES['photo']['type'], $allowedTypes, true)) {
            $errors['photo'] = 'Only JPG, PNG, or WEBP images are allowed.';
        } elseif ($_FILES['photo']['size'] > $maxSize) {
            $errors['photo'] = 'Photo must be smaller than 2MB.';
        } else {
            $ext        = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $newPhoto   = 'stu_' . uniqid() . '.' . strtolower($ext);
            $destination = __DIR__ . '/uploads/' . $newPhoto;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                // remove old photo file if it existed
                if (!empty($student['photo']) && file_exists(__DIR__ . '/uploads/' . $student['photo'])) {
                    unlink(__DIR__ . '/uploads/' . $student['photo']);
                }
                $photoFilename = $newPhoto;
            } else {
                $errors['photo'] = 'Failed to save uploaded photo.';
            }
        }
    }

    if (empty($errors)) {
        $update = $pdo->prepare(
            "UPDATE students
             SET name = :name, email = :email, branch = :branch,
                 cgpa = :cgpa, status = :status, photo = :photo
             WHERE id = :id"
        );
        $update->execute([
            ':name'   => $old['name'],
            ':email'  => $old['email'],
            ':branch' => $old['branch'],
            ':cgpa'   => $old['cgpa'],
            ':status' => $old['status'],
            ':photo'  => $photoFilename,
            ':id'     => $id,
        ]);

        header('Location: index.php?success=updated');
        exit;
    }
}

include 'includes/header.php';
?>

<div class="card" style="max-width: 640px; margin: 0 auto;">
  <div class="card-body p-4">
    <h4 class="mb-4"><i class="bi bi-pencil-fill text-primary"></i> Edit Student</h4>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill"></i> Please fix the errors below.
      </div>
    <?php endif; ?>

    <?php if (!empty($student['photo']) && file_exists('uploads/' . $student['photo'])): ?>
      <div class="mb-3 text-center">
        <img src="uploads/<?php echo htmlspecialchars($student['photo']); ?>"
             class="rounded-circle" style="width:90px;height:90px;object-fit:cover;">
      </div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="id" value="<?php echo $id; ?>">

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
        <label class="form-label">Replace Photo (optional)</label>
        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
               class="form-control <?php echo isset($errors['photo']) ? 'is-invalid' : ''; ?>">
        <div class="form-text">Leave empty to keep the current photo.</div>
        <?php if (isset($errors['photo'])): ?><div class="invalid-feedback"><?php echo $errors['photo']; ?></div><?php endif; ?>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle-fill"></i> Update Student
        </button>
        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

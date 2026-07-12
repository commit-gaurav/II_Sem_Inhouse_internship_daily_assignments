<?php
require "auth.php";
require "db.php";
require "includes/upload_helper.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$student = mysqli_stmt_get_result($stmt)->fetch_assoc();
mysqli_stmt_close($stmt);

if (!$student) {
    $_SESSION['flash'] = ["type" => "danger", "message" => "Student not found."];
    header("Location: students.php");
    exit;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"]);
    $branch  = trim($_POST["branch"]);
    $cgpa    = trim($_POST["cgpa"]);
    $address = trim($_POST["address"]);
    $course  = trim($_POST["course"]);

    $photo_name = $student["photo"]; // keep the existing photo unless a new one is uploaded

    if (!empty($_FILES["photo"]["name"])) {
        $upload = handle_photo_upload($_FILES["photo"]);
        if ($upload["ok"]) {
            // Remove the old photo file so uploads/ doesn't accumulate orphaned files.
            if (!empty($student["photo"]) && file_exists(__DIR__ . "/uploads/" . $student["photo"])) {
                unlink(__DIR__ . "/uploads/" . $student["photo"]);
            }
            $photo_name = $upload["filename"];
        } else {
            $errors[] = $upload["error"];
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE students SET name = ?, branch = ?, cgpa = ?, photo = ?, address = ?, course = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "ssssssi", $name, $branch, $cgpa, $photo_name, $address, $course, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash'] = ["type" => "success", "message" => "Student updated successfully!"];
            mysqli_stmt_close($stmt);
            header("Location: students.php");
            exit;
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

$page_title = "Edit Student";
require "includes/header.php";
?>

<div class="card shadow-sm fade-in-card">
  <div class="card-body">
    <h1 class="h4 mb-4"><i class="bi bi-pencil-square text-primary"></i> Edit Student</h1>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-3">

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person"></i> Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-diagram-3"></i> Branch</label>
            <input type="text" name="branch" class="form-control" value="<?php echo htmlspecialchars($student['branch']); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-award"></i> CGPA</label>
            <input type="number" step="0.01" min="0" max="10" name="cgpa" class="form-control" value="<?php echo htmlspecialchars($student['cgpa']); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-book"></i> Course</label>
            <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($student['course']); ?>">
        </div>

        <div class="col-12">
            <label class="form-label"><i class="bi bi-geo-alt"></i> Address</label>
            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-camera"></i> Replace Photo (optional)</label>
            <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">
            <div class="form-text">Leave empty to keep the current photo.</div>
        </div>

        <div class="col-md-6 d-flex align-items-end gap-2">
            <?php if (!empty($student['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($student['photo']); ?>" alt="Current photo"
                     class="img-thumbnail" style="max-height: 100px;">
            <?php endif; ?>
            <img id="photoPreview" src="" alt="New photo preview" class="img-thumbnail d-none" style="max-height: 100px;">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Changes</button>
            <a href="students.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
  </div>
</div>

<?php require "includes/footer.php"; ?>

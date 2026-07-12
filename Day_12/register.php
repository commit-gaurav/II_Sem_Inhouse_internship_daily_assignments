<?php
require "auth.php";
require "db.php";
require "includes/upload_helper.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Grab the text fields. trim() removes stray spaces.
    $name    = trim($_POST["name"]);
    $branch  = trim($_POST["branch"]);
    $cgpa    = trim($_POST["cgpa"]);
    $address = trim($_POST["address"]);
    $course  = trim($_POST["course"]);

    // 2. Handle the photo upload through the shared, validated helper.
    $photo_name = "";
    if (!empty($_FILES["photo"]["name"])) {
        $upload = handle_photo_upload($_FILES["photo"]);
        if ($upload["ok"]) {
            $photo_name = $upload["filename"];
        } else {
            $errors[] = $upload["error"];
        }
    }

    // 3. Insert into the database using a prepared statement.
    // Prepared statements (with ? placeholders) protect against SQL injection -
    // never build a query by concatenating $_POST values directly into SQL.
    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO students (name, branch, cgpa, photo, address, course)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $branch, $cgpa, $photo_name, $address, $course);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect-after-POST: prevents the classic "resubmit form?"
            // bug if the user refreshes this page after submitting.
            $_SESSION['flash'] = ["type" => "success", "message" => "Student registered successfully!"];
            mysqli_stmt_close($stmt);
            header("Location: students.php");
            exit;
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

$page_title = "Register Student";
require "includes/header.php";
?>

<div class="card shadow-sm fade-in-card">
  <div class="card-body">
    <h1 class="h4 mb-4"><i class="bi bi-person-plus-fill text-primary"></i> Register Student</h1>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <!-- enctype="multipart/form-data" is required whenever a form includes a file input -->
    <form method="POST" enctype="multipart/form-data" class="row g-3">

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person"></i> Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-diagram-3"></i> Branch</label>
            <input type="text" name="branch" class="form-control" value="<?php echo htmlspecialchars($_POST['branch'] ?? ''); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-award"></i> CGPA</label>
            <input type="number" step="0.01" min="0" max="10" name="cgpa" class="form-control" value="<?php echo htmlspecialchars($_POST['cgpa'] ?? ''); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-book"></i> Course</label>
            <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($_POST['course'] ?? ''); ?>">
        </div>

        <div class="col-12">
            <label class="form-label"><i class="bi bi-geo-alt"></i> Address</label>
            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-camera"></i> Photo</label>
            <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">
            <div class="form-text">JPG, PNG, GIF, or WEBP. Max 2MB.</div>
        </div>

        <div class="col-md-6 d-flex align-items-end">
            <img id="photoPreview" src="" alt="Preview" class="img-thumbnail d-none" style="max-height: 100px;">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Register</button>
            <a href="students.php" class="btn btn-outline-secondary">View all students</a>
        </div>
    </form>
  </div>
</div>

<?php require "includes/footer.php"; ?>

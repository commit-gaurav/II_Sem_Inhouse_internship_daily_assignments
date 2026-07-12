<?php
require "db.php";

$message = ""; // will hold a success/error message after submit

// This block only runs when the form is submitted (POST request).
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Grab the text fields. trim() removes stray spaces.
    $name    = trim($_POST["name"]);
    $branch  = trim($_POST["branch"]);
    $cgpa    = trim($_POST["cgpa"]);
    $address = trim($_POST["address"]);
    $course  = trim($_POST["course"]);

    // 2. Handle the photo upload.
    // $_FILES holds any uploaded file. We only proceed if one was actually chosen.
    $photo_name = "";
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $original_name = basename($_FILES["photo"]["name"]);

        // Prefix with time() so two students uploading "photo.jpg" don't overwrite each other.
        $photo_name = time() . "_" . $original_name;
        $destination = "uploads/" . $photo_name;

        move_uploaded_file($_FILES["photo"]["tmp_name"], $destination);
    }

    // 3. Insert into the database using a prepared statement.
    // Prepared statements (with ? placeholders) protect against SQL injection -
    // never build a query by concatenating $_POST values directly into SQL.
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO students (name, branch, cgpa, photo, address, course)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    // "sssdss"-style type string: s = string, d = double(decimal). Here cgpa is
    // stored as text from the form, so we bind it as a string and let MySQL cast it.
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $branch, $cgpa, $photo_name, $address, $course);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Student registered successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h1 class="mb-4">Register Student</h1>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- enctype="multipart/form-data" is required whenever a form includes a file input -->
    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Branch</label>
            <input type="text" name="branch" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">CGPA</label>
            <input type="number" step="0.01" min="0" max="10" name="cgpa" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <a href="students.php" class="d-block mt-3">View all students →</a>

</body>
</html>

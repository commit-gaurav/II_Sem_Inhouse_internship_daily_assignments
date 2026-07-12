<?php
require "auth.php";
require "db.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT photo FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$student = mysqli_stmt_get_result($stmt)->fetch_assoc();
mysqli_stmt_close($stmt);

if ($student) {
    // Clean up the photo file so uploads/ doesn't fill with orphaned images.
    if (!empty($student["photo"]) && file_exists(__DIR__ . "/uploads/" . $student["photo"])) {
        unlink(__DIR__ . "/uploads/" . $student["photo"]);
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['flash'] = ["type" => "success", "message" => "Student record deleted."];
} else {
    $_SESSION['flash'] = ["type" => "danger", "message" => "Student not found."];
}

header("Location: students.php");
exit;

// Note: this uses a confirm() dialog + GET link for simplicity, which is
// fine for a college project. A production app would use a POST form with
// a CSRF token so the delete can't be triggered by just visiting a URL.

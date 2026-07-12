<?php
// Collect submitted values (trimmed so blank spaces don't pass as valid)
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$branch  = trim($_POST['branch'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$gender  = trim($_POST['gender'] ?? '');
$course  = trim($_POST['course'] ?? '');
$address = trim($_POST['address'] ?? '');
$photoName = $_FILES['photo']['name'] ?? '';

$errors = [];

// Name: required, no digits allowed
if ($name === '') {
    $errors[] = "Name is required.";
} elseif (preg_match('/[0-9]/', $name)) {
    $errors[] = "Name should not contain numbers.";
}

// Email: required, valid format
if ($email === '') {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// Branch: required
if ($branch === '') {
    $errors[] = "Branch is required.";
}

// Phone: required, exactly 10 digits
if ($phone === '') {
    $errors[] = "Phone number is required.";
} elseif (!preg_match('/^\d{10}$/', $phone)) {
    $errors[] = "Phone number must be exactly 10 digits.";
}

// Gender: required
if ($gender === '') {
    $errors[] = "Please select a gender.";
}

// Course: required
if ($course === '') {
    $errors[] = "Please select a course.";
}

// Address: required, minimum length
if ($address === '') {
    $errors[] = "Address is required.";
} elseif (strlen($address) < 10) {
    $errors[] = "Address must be at least 10 characters long.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Result</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5 mb-5">

<?php if (!empty($errors)): ?>

    <h2 class="mb-4">Please fix the following errors</h2>
    <div class="error-box">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <a href="register.html" class="btn btn-secondary">Go Back</a>

<?php else: ?>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">Registration Successful</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Branch:</strong> <?= htmlspecialchars($branch) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($gender) ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($course) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
            <p><strong>Photo File:</strong>
                <?= $photoName ? htmlspecialchars($photoName) . " (not stored, front-end only)" : "No photo uploaded" ?>
            </p>
        </div>
    </div>
    <a href="register.html" class="btn btn-primary mt-3">Register Another Student</a>

<?php endif; ?>

</div>
</body>
</html>

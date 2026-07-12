<?php
include 'header.php';

// -----------------------------------------------------
// STEP 1: Collect the submitted data
// $_POST is a superglobal array PHP fills automatically
// with whatever was sent from the form via method="POST".
// We use the null coalescing operator (??) so that if a
// field is missing entirely, we get an empty string
// instead of a PHP "undefined array key" warning.
// -----------------------------------------------------
$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$cgpa    = $_POST['cgpa'] ?? '';
$branch  = $_POST['branch'] ?? '';
$college = $_POST['college'] ?? '';
$gender  = $_POST['gender'] ?? '';
$course  = $_POST['course'] ?? '';
$address = $_POST['address'] ?? '';

// -----------------------------------------------------
// STEP 2: Validation
// We collect every error into an array instead of stopping
// at the first one, so the user can see everything wrong
// with their submission at once.
// trim() removes leading/trailing spaces so a field with
// just spaces in it doesn't pass as "filled in".
// -----------------------------------------------------
$errors = [];

if (trim($name) === '')    $errors[] = "Name is required.";
if (trim($email) === '')   $errors[] = "Email is required.";
if (trim($cgpa) === '')    $errors[] = "CGPA is required.";
if (trim($branch) === '')  $errors[] = "Branch is required.";
if (trim($college) === '') $errors[] = "College is required.";
if (trim($gender) === '')  $errors[] = "Please select a gender.";
if (trim($course) === '')  $errors[] = "Please select a course.";
if (trim($address) === '') $errors[] = "Address is required.";

// Extra check: CGPA must actually be a number between 0 and 10
if (trim($cgpa) !== '' && (!is_numeric($cgpa) || $cgpa < 0 || $cgpa > 10)) {
    $errors[] = "CGPA must be a number between 0 and 10.";
}

// Extra check: basic email format validation using PHP's built-in filter
if (trim($email) !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// -----------------------------------------------------
// STEP 3: calculateGrade() function
// Takes the CGPA and returns both a letter grade and a
// Bootstrap alert color class to match it visually.
// -----------------------------------------------------
function calculateGrade($cgpa) {
    if ($cgpa >= 9) {
        return ['grade' => 'A+', 'class' => 'success'];
    } elseif ($cgpa >= 8) {
        return ['grade' => 'A', 'class' => 'primary'];
    } elseif ($cgpa >= 7) {
        return ['grade' => 'B', 'class' => 'info'];
    } elseif ($cgpa >= 6) {
        return ['grade' => 'C', 'class' => 'warning'];
    } else {
        return ['grade' => 'D', 'class' => 'danger'];
    }
}
?>

<?php if (!empty($errors)): ?>

    <!-- If there are validation errors, show them and stop here -->
    <div class="alert alert-danger">
        <h5><i class="fa-solid fa-triangle-exclamation"></i> Please fix the following:</h5>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="index.php" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Go Back
    </a>

<?php else: ?>

    <?php
    // Only run this if validation passed
    $result = calculateGrade($cgpa);
    ?>

    <div class="card shadow-sm">
        <div class="confirmation-header text-center">
            <h4 class="mb-0"><i class="fa-solid fa-circle-check"></i> Registration Confirmed</h4>
        </div>

        <div class="card-body">

            <!-- Profile photo placeholder (bonus feature) -->
            <div class="profile-placeholder">
                <i class="fa-solid fa-user"></i>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <!-- htmlspecialchars() converts special characters like < > " '
                         into their HTML-safe equivalents, so if someone typed
                         something like <script> into the form it is displayed
                         as plain text instead of being executed. This prevents
                         a type of attack called XSS (Cross-Site Scripting). -->
                    <td><?php echo htmlspecialchars($name); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($email); ?></td>
                </tr>
                <tr>
                    <th>CGPA</th>
                    <td><?php echo htmlspecialchars($cgpa); ?></td>
                </tr>
                <tr>
                    <th>Grade</th>
                    <td>
                        <span class="badge bg-<?php echo $result['class']; ?>">
                            <?php echo $result['grade']; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td><?php echo htmlspecialchars($branch); ?></td>
                </tr>
                <tr>
                    <th>College</th>
                    <td><?php echo htmlspecialchars($college); ?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?php echo htmlspecialchars($gender); ?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?php echo htmlspecialchars($course); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo htmlspecialchars($address); ?></td>
                </tr>
                <tr>
                    <th>Date of Registration</th>
                    <td><?php echo date("d-m-Y"); ?></td>
                </tr>
            </table>

            <a href="index.php" class="btn btn-primary w-100">
                <i class="fa-solid fa-rotate-left"></i> Register Another Student
            </a>

        </div>
    </div>

<?php endif; ?>

<?php include 'footer.php'; ?>

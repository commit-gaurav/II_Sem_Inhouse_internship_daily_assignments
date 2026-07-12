<?php
// Included by every page after auth.php + db.php have already run.
// Expects $page_title to be set by the including page.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title ?? 'NexaLearn'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo isset($_SESSION['user_id']) ? 'dashboard.php' : 'login.php'; ?>">
            <i class="bi bi-mortarboard-fill"></i> NexaLearn
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="d-flex align-items-center flex-wrap gap-3">
            <ul class="navbar-nav flex-row gap-3 mb-0">
                <li><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a class="nav-link" href="students.php"><i class="bi bi-people-fill"></i> Students</a></li>
                <li><a class="nav-link" href="register.php"><i class="bi bi-person-plus-fill"></i> Register</a></li>
            </ul>
            <span class="text-light small">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container pb-5">

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-<?php echo htmlspecialchars($_SESSION['flash']['type']); ?> alert-dismissible fade show fade-in-card">
        <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

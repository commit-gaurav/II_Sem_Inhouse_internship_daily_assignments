<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : ''; ?>Student Management Portal</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Custom styles -->
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#1f2937;">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="bi bi-mortarboard-fill"></i> Student Management Portal
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="bi bi-table"></i> Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add.php"><i class="bi bi-person-plus-fill"></i> Add Student</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">

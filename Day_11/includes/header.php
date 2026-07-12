<?php
/**
 * includes/header.php — shared top navbar + sidebar shell.
 * Expects $pageTitle to be set by the including page.
 * Requires $_SESSION['user_name'] / user_id / user_avatar to be set (see login.php).
 */
$pageTitle = $pageTitle ?? 'NexaLearn';
$avatar = !empty($_SESSION['user_avatar'])
    ? 'uploads/avatars/' . htmlspecialchars($_SESSION['user_avatar'])
    : 'https://ui-avatars.com/api/?background=0d6efd&color=fff&name=' . urlencode($_SESSION['user_name'] ?? 'U');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> · NexaLearn</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body { background:#f4f6f9; }
  .sidebar {
    min-height: 100vh; background:#0d1b2a; color:#fff; width:230px;
  }
  .sidebar a { color:#b8c2cc; text-decoration:none; display:block; padding:.65rem 1.2rem; border-radius:.4rem; }
  .sidebar a:hover, .sidebar a.active { background:#1b2a3d; color:#fff; }
  .sidebar .brand { font-weight:700; font-size:1.15rem; padding:1.2rem; color:#fff; }
  .navbar-user img { width:36px; height:36px; object-fit:cover; border-radius:50%; }
  .app-wrapper { display:flex; }
  .main-content { flex:1; }
</style>
</head>
<body>
<div class="app-wrapper">
  <nav class="sidebar d-flex flex-column py-2">
    <div class="brand"><i class="bi bi-mortarboard-fill"></i> NexaLearn</div>
    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="profile.php" class="<?= basename($_SERVER['PHP_SELF'])=='profile.php'?'active':'' ?>"><i class="bi bi-person-circle me-2"></i>Profile</a>
    <a href="change_password.php" class="<?= basename($_SERVER['PHP_SELF'])=='change_password.php'?'active':'' ?>"><i class="bi bi-shield-lock me-2"></i>Change Password</a>
    <a href="logout.php" class="mt-auto text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </nav>

  <div class="main-content">
    <nav class="navbar navbar-light bg-white border-bottom px-4 py-2">
      <span class="navbar-brand fw-semibold"><?= htmlspecialchars($pageTitle) ?></span>
      <div class="navbar-user d-flex align-items-center gap-2 ms-auto">
        <img src="<?= $avatar ?>" alt="avatar">
        <span class="fw-medium"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
      </div>
    </nav>
    <div class="p-4">

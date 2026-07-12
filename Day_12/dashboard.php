<?php
require "auth.php";
require "db.php";

$total     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM students"))["c"];
$avg_cgpa  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ROUND(AVG(cgpa), 2) AS a FROM students"))["a"];
$top_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM students WHERE cgpa > 8.0"))["c"];

$recent = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC LIMIT 5");

$page_title = "Dashboard";
require "includes/header.php";
?>

<h1 class="h4 mb-4"><i class="bi bi-speedometer2 text-primary"></i> Dashboard</h1>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm stat-card fade-in-card">
            <div class="card-body text-center">
                <i class="bi bi-people-fill fs-2 text-primary"></i>
                <div class="display-6"><?php echo (int) $total; ?></div>
                <div class="text-muted">Total Students</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm stat-card fade-in-card">
            <div class="card-body text-center">
                <i class="bi bi-award-fill fs-2 text-success"></i>
                <div class="display-6"><?php echo $avg_cgpa ?? '—'; ?></div>
                <div class="text-muted">Average CGPA</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm stat-card fade-in-card">
            <div class="card-body text-center">
                <i class="bi bi-star-fill fs-2 text-warning"></i>
                <div class="display-6"><?php echo (int) $top_count; ?></div>
                <div class="text-muted">CGPA above 8.0</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm fade-in-card">
  <div class="card-body">
    <h2 class="h5 mb-3"><i class="bi bi-clock-history"></i> Recent Registrations</h2>
    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Branch</th>
                <th>CGPA</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($recent) === 0): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">No students registered yet.</td></tr>
            <?php endif; ?>
            <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                <tr class="fade-in-card">
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["branch"]); ?></td>
                    <td><?php echo htmlspecialchars($row["cgpa"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_registered"]); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    <a href="students.php" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-arrow-right"></i> View all students</a>
  </div>
</div>

<?php require "includes/footer.php"; ?>

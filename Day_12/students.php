<?php
require "auth.php";
require "db.php";

// Search box (bonus... well, required this time) - matches name, branch, or
// course. Uses a prepared statement with LIKE so it's still injection-safe.
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $like = "%" . $search . "%";
    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM students WHERE name LIKE ? OR branch LIKE ? OR course LIKE ? ORDER BY id DESC"
    );
    mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
}

// mysqli_num_rows() counts how many rows the query returned - used for the
// "Total Students: N" line at the bottom.
$total_students = mysqli_num_rows($result);

$page_title = "Student List";
require "includes/header.php";
?>

<div class="card shadow-sm fade-in-card">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="h4 mb-0"><i class="bi bi-people-fill text-primary"></i> Registered Students</h1>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control" placeholder="Search name, branch, course..."
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            <?php if ($search !== ''): ?>
                <a href="students.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- table-hover: highlights a row on mouseover
         table-bordered: adds borders around every cell -->
    <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Branch</th>
                <th>Course</th>
                <th>CGPA</th>
                <th>Address</th>
                <th>Date Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_students === 0): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-inbox"></i>
                        No students found<?php echo $search !== '' ? ' for "' . htmlspecialchars($search) . '"' : ''; ?>.
                    </td>
                </tr>
            <?php endif; ?>
            <?php
            // mysqli_fetch_assoc() pulls one row at a time as an associative array,
            // e.g. $row["name"], $row["cgpa"]. The while loop runs once per row
            // until there are none left, at which point it returns false/null.
            while ($row = mysqli_fetch_assoc($result)) {

                // Highlight strong students.
                $row_class = "";
                if ($row["cgpa"] > 8.0) {
                    $row_class = "table-success";
                }
                ?>
                <tr class="<?php echo $row_class; ?> fade-in-card">
                    <td><?php echo $row["id"]; ?></td>
                    <td>
                        <?php if (!empty($row["photo"])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row["photo"]); ?>"
                                 alt="<?php echo htmlspecialchars($row["name"]); ?>" width="50" height="50"
                                 class="rounded" style="object-fit: cover;">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-3 text-muted"></i>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["branch"]); ?></td>
                    <td><?php echo htmlspecialchars($row["course"]); ?></td>
                    <td><?php echo htmlspecialchars($row["cgpa"]); ?></td>
                    <td><?php echo htmlspecialchars($row["address"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_registered"]); ?></td>
                    <td class="text-nowrap">
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete"
                           onclick="return confirm('Delete this student record? This cannot be undone.');">
                            <i class="bi bi-trash3"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    </div>

    <!-- Total row count -->
    <p class="fw-bold mb-0"><i class="bi bi-people"></i> Total Students: <?php echo $total_students; ?></p>

  </div>
</div>

<div class="mt-3">
    <a href="register.php" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Register another student</a>
</div>

<?php require "includes/footer.php"; ?>

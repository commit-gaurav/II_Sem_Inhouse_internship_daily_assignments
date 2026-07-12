<?php
require 'config/db.php';
$pageTitle = 'Dashboard';

// ---------------------------------------------------------
// 1. DASHBOARD STATS (SQL aggregate queries)
// ---------------------------------------------------------
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$avgCgpaRow    = $pdo->query("SELECT ROUND(AVG(cgpa), 2) AS avg_cgpa FROM students")->fetch();
$avgCgpa       = $avgCgpaRow['avg_cgpa'] ?? 0;

$perBranchStmt = $pdo->query("SELECT branch, COUNT(*) AS total FROM students GROUP BY branch ORDER BY total DESC");
$perBranch     = $perBranchStmt->fetchAll();

// distinct branch list for the filter dropdown
$branchListStmt = $pdo->query("SELECT DISTINCT branch FROM students ORDER BY branch");
$branchList     = $branchListStmt->fetchAll(PDO::FETCH_COLUMN);

// ---------------------------------------------------------
// 2. READ FILTER / SEARCH INPUT
// ---------------------------------------------------------
$q          = trim($_GET['q'] ?? '');
$branch     = trim($_GET['branch'] ?? '');
$statusView = trim($_GET['status'] ?? 'Active'); // default: only active students
$minCgpa    = $_GET['min_cgpa'] ?? '';
$maxCgpa    = $_GET['max_cgpa'] ?? '';

// ---------------------------------------------------------
// 3. BUILD COMBINED WHERE CLAUSE (multi-field search + filters)
// ---------------------------------------------------------
$where  = [];
$params = [];

if ($q !== '') {
    // Multi-field search across name, email, branch simultaneously
    $where[]        = "(name LIKE :q OR email LIKE :q OR branch LIKE :q)";
    $params[':q']   = '%' . $q . '%';
}

if ($branch !== '') {
    $where[]           = "branch = :branch";
    $params[':branch'] = $branch;
}

if ($statusView === 'Active' || $statusView === 'Inactive') {
    $where[]           = "status = :status";
    $params[':status'] = $statusView;
}
// $statusView === 'All' -> no status filter applied

if ($minCgpa !== '' && is_numeric($minCgpa)) {
    $where[]             = "cgpa >= :min_cgpa";
    $params[':min_cgpa'] = $minCgpa;
}

if ($maxCgpa !== '' && is_numeric($maxCgpa)) {
    $where[]             = "cgpa <= :max_cgpa";
    $params[':max_cgpa'] = $maxCgpa;
}

$sql = "SELECT * FROM students";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

include 'includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i>
    <?php
      $messages = [
        'added'   => 'Student added successfully.',
        'updated' => 'Student updated successfully.',
        'deleted' => 'Student deleted successfully.',
      ];
      echo htmlspecialchars($messages[$_GET['success']] ?? 'Done.');
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- ===================== STATS ROW ===================== -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="stat-card bg-total">
      <div class="stat-label"><i class="bi bi-people-fill"></i> Total Students</div>
      <div class="stat-value"><?php echo (int) $totalStudents; ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card bg-avg">
      <div class="stat-label"><i class="bi bi-graph-up"></i> Average CGPA</div>
      <div class="stat-value"><?php echo htmlspecialchars($avgCgpa); ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card bg-branch">
      <div class="stat-label"><i class="bi bi-diagram-3-fill"></i> Students per Branch</div>
      <div class="mt-2">
        <?php foreach ($perBranch as $b): ?>
          <span class="badge bg-light text-dark me-1 mb-1">
            <?php echo htmlspecialchars($b['branch']); ?>: <?php echo (int) $b['total']; ?>
          </span>
        <?php endforeach; ?>
        <?php if (empty($perBranch)): ?>
          <span class="small">No data yet</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- ===================== SEARCH & FILTER BAR ===================== -->
<div class="card mb-4">
  <div class="card-body">
    <form method="GET" action="index.php" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label small text-muted">Search (name, email, branch)</label>
        <input type="text" name="q" class="form-control" placeholder="e.g. Priya or CSE"
               value="<?php echo htmlspecialchars($q); ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted">Branch / Course</label>
        <select name="branch" class="form-select">
          <option value="">All Branches</option>
          <?php foreach ($branchList as $b): ?>
            <option value="<?php echo htmlspecialchars($b); ?>" <?php echo $branch === $b ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($b); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted">Status</label>
        <select name="status" class="form-select">
          <option value="Active"   <?php echo $statusView === 'Active'   ? 'selected' : ''; ?>>Active only</option>
          <option value="Inactive" <?php echo $statusView === 'Inactive' ? 'selected' : ''; ?>>Inactive only</option>
          <option value="All"      <?php echo $statusView === 'All'      ? 'selected' : ''; ?>>View all</option>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted">Min CGPA</label>
        <input type="number" step="0.01" min="0" max="10" name="min_cgpa" class="form-control"
               value="<?php echo htmlspecialchars($minCgpa); ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted">Max CGPA</label>
        <input type="number" step="0.01" min="0" max="10" name="max_cgpa" class="form-control"
               value="<?php echo htmlspecialchars($maxCgpa); ?>">
      </div>

      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search"></i> Apply Filters
        </button>
        <a href="index.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-counterclockwise"></i> Reset
        </a>
        <span class="ms-auto align-self-center text-muted small">
          <?php echo count($students); ?> record(s) found
        </span>
      </div>
    </form>
  </div>
</div>

<!-- ===================== STUDENTS TABLE ===================== -->
<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Email</th>
          <th>Branch</th>
          <th>CGPA</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($students)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="bi bi-inbox display-6 d-block mb-2"></i>
              No students found. Try adjusting your filters or
              <a href="add.php">add a new student</a>.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($students as $s): ?>
            <tr>
              <td>
                <?php if (!empty($s['photo']) && file_exists('uploads/' . $s['photo'])): ?>
                  <img src="uploads/<?php echo htmlspecialchars($s['photo']); ?>" class="student-photo" alt="Photo">
                <?php else: ?>
                  <div class="photo-placeholder"><i class="bi bi-person-fill"></i></div>
                <?php endif; ?>
              </td>
              <td class="fw-semibold"><?php echo htmlspecialchars($s['name']); ?></td>
              <td><?php echo htmlspecialchars($s['email']); ?></td>
              <td><span class="badge badge-branch"><?php echo htmlspecialchars($s['branch']); ?></span></td>
              <td><?php echo htmlspecialchars($s['cgpa']); ?></td>
              <td>
                <?php if ($s['status'] === 'Active'): ?>
                  <span class="badge bg-success">Active</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactive</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <a href="edit.php?id=<?php echo (int) $s['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="confirmDelete(<?php echo (int) $s['id']; ?>, '<?php echo htmlspecialchars(addslashes($s['name'])); ?>')"
                        title="Delete">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong id="deleteStudentName"></strong>? This cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
          <i class="bi bi-trash-fill"></i> Delete
        </a>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(id, name) {
  document.getElementById('deleteStudentName').textContent = name;
  document.getElementById('confirmDeleteBtn').href = 'delete.php?id=' + id;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php include 'includes/footer.php'; ?>

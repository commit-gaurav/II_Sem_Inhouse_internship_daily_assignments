<?php
require "db.php";

// SELECT * to fetch every column, including the new ones from tonight's assignment.
$result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");

// mysqli_num_rows() counts how many rows the query returned - used for the
// "Total Students: N" line at the bottom (the bonus task).
$total_students = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h1 class="mb-4">Registered Students</h1>

    <!-- table-hover: highlights a row on mouseover
         table-bordered: adds borders around every cell -->
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
            </tr>
        </thead>
        <tbody>
            <?php
            // mysqli_fetch_assoc() pulls one row at a time as an associative array,
            // e.g. $row["name"], $row["cgpa"]. The while loop runs once per row
            // until there are none left, at which point it returns false/null.
            while ($row = mysqli_fetch_assoc($result)) {

                // Challenge: highlight strong students.
                // We build the row's CSS class in a PHP variable first, then print
                // it into the <tr> tag, rather than putting an if/else inside the HTML.
                $row_class = "";
                if ($row["cgpa"] > 8.0) {
                    $row_class = "table-success";
                }
                ?>
                <tr class="<?php echo $row_class; ?>">
                    <td><?php echo $row["id"]; ?></td>
                    <td>
                        <?php if (!empty($row["photo"])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row["photo"]); ?>"
                                 alt="photo" width="50" height="50" style="object-fit: cover;">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["branch"]); ?></td>
                    <td><?php echo htmlspecialchars($row["course"]); ?></td>
                    <td><?php echo htmlspecialchars($row["cgpa"]); ?></td>
                    <td><?php echo htmlspecialchars($row["address"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_registered"]); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <!-- Bonus task: total row count -->
    <p class="fw-bold">Total Students: <?php echo $total_students; ?></p>

    <a href="register.php">← Register another student</a>

</body>
</html>

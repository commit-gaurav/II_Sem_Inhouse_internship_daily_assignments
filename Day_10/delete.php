<?php
require 'config/db.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    // Fetch photo filename first so we can remove the file too
    $stmt = $pdo->prepare("SELECT photo FROM students WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $student = $stmt->fetch();

    if ($student) {
        $delete = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $delete->execute([':id' => $id]);

        if (!empty($student['photo']) && file_exists(__DIR__ . '/uploads/' . $student['photo'])) {
            unlink(__DIR__ . '/uploads/' . $student['photo']);
        }
    }
}

header('Location: index.php?success=deleted');
exit;

<?php
/**
 * One-time helper: run this once from the browser or CLI to print an
 * environment-correct bcrypt hash for the sample admin password, then
 * paste it into the users table (or run the UPDATE shown below).
 *
 * php reset_admin_password.php
 */
require_once __DIR__ . '/db.php';

$plainPassword = 'Admin@123';
$hash = password_hash($plainPassword, PASSWORD_DEFAULT);

echo "Hash for '{$plainPassword}':\n{$hash}\n\n";

// Automatically fix the sample admin row for you.
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hash, $email = 'admin@nexalearn.com');
if ($stmt->execute()) {
    echo "admin@nexalearn.com password updated successfully. You can now log in with Admin@123.\n";
} else {
    echo "Update failed: " . $stmt->error . "\n";
}
$stmt->close();
$conn->close();

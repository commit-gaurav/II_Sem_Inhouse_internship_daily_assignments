<?php
/**
 * db.php
 * -------
 * One place to connect to MySQL. Every other page includes this file
 * instead of writing its own mysqli_connect() call, so if the password
 * or database name ever changes, you only edit it here.
 */

$db_host = "localhost";
$db_user = "root";       // change to your MySQL username
$db_pass = "";           // change to your MySQL password
$db_name = "nexalearn";  // the database this project uses

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    // die() stops the script immediately and prints the error.
    // Fine for a college project; a real app would log this instead
    // of showing raw DB errors to the user.
    die("Connection failed: " . mysqli_connect_error());
}

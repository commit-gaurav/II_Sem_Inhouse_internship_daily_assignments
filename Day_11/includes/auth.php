<?php
/**
 * includes/auth.php — session bootstrap + protected-page guard.
 * Include this (after db.php) at the very top of any page that
 * should only be visible to logged-in users.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: access_denied.php');
        exit;
    }
}

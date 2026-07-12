<?php
/**
 * auth.php
 * --------
 * Include this at the very top of any page that should only be visible
 * to logged-in users - before any HTML or other output is printed,
 * since header() must run before anything is sent to the browser.
 *
 * It starts the session and immediately bounces anonymous visitors to
 * the login page. Every protected page (dashboard, students, register,
 * edit, delete) starts with:
 *
 *     require "auth.php";
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

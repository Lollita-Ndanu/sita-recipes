<?php
require_once __DIR__ . "/../config.php";

function start_admin_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_admin_logged_in()
{
    start_admin_session();
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin_login()
{
    if (!is_admin_logged_in()) {
        // Send unauthenticated users to the admin landing page.
        header("Location: ./");
        exit;
    }
}
?>

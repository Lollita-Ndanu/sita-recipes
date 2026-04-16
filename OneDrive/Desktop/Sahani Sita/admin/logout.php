<?php
require_once __DIR__ . "/../includes/auth.php";

start_admin_session();

$_SESSION = array();
session_destroy();

header("Location: ./");
exit;
?>

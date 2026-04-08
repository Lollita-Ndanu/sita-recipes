<?php
require_once "../includes/auth.php";

start_admin_session();

$_SESSION = array();
session_destroy();

header("Location: ./");
exit;
?>

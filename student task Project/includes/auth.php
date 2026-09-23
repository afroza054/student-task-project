<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<?php
require_once "includes/auth.php";
require_once "config/database.php";
?>

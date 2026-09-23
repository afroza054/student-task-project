<?php

require_once "includes/auth.php";
require_once "config/database.php";

$taskId = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$taskId) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("
    DELETE FROM tasks
    WHERE id = ? AND user_id = ?
");

$stmt->execute([
    $taskId,
    $_SESSION["user_id"]
]);

header("Location: dashboard.php");

exit;
?>

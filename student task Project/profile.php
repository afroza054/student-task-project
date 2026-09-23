<?php

require_once "includes/auth.php";
require_once "config/database.php";

$stmt = $pdo->prepare("
    SELECT name, email, created_at
    FROM users
    WHERE id = ?
");

$stmt->execute([
    $_SESSION["user_id"]
]);

$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Profile - Student Task Manager</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">
        Student Task Manager
    </div>

    <div>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>

<main class="form-container">

    <div class="form-card">

        <h1>My Profile</h1>

        <p>
            <strong>Name:</strong>
            <?= htmlspecialchars($user["name"]) ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= htmlspecialchars($user["email"]) ?>
        </p>

        <p>
            <strong>Account Created:</strong>
            <?= htmlspecialchars($user["created_at"]) ?>
        </p>

        <br>

        <a
            href="dashboard.php"
            class="btn"
        >
            Back to Dashboard
        </a>

    </div>

</main>
<script src="js/script.js"></script>

</body>

</html>

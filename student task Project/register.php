<?php

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($name === "" || $email === "" || $password === "") {
        $message = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";

    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $message = "An account with this email already exists.";
        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $email,
                $hashedPassword
            ]);

            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Task Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="auth-container">

    <div class="auth-box">

        <h1>Create Account</h1>
        <p>Register for Student Task Manager</p>

        <?php if ($message): ?>
            <div class="error">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Name</label>
            <input
                type="text"
                name="name"
                required
            >

            <label>Email</label>
            <input
                type="email"
                name="email"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >

            <label>Confirm Password</label>
            <input
                type="password"
                name="confirm_password"
                required
            >

            <button type="submit">
                Register
            </button>

        </form>

        <p>
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>
<script src="js/script.js"></script>

</body>
</html>

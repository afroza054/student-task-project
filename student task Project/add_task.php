<?php

require_once "includes/auth.php";
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $priority = $_POST["priority"];
    $status = $_POST["status"];
    $dueDate = $_POST["due_date"];

    $validPriorities = ["Low", "Medium", "High"];

    $validStatuses = [
        "Pending",
        "In Progress",
        "Completed"
    ];

    if ($title === "") {

        $message = "Task title is required.";

    } elseif (strlen($title) > 150) {

        $message = "Task title cannot exceed 150 characters.";

    } elseif (!in_array($priority, $validPriorities, true)) {

        $message = "Invalid priority.";

    } elseif (!in_array($status, $validStatuses, true)) {

        $message = "Invalid status.";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO tasks
            (
                user_id,
                title,
                description,
                priority,
                status,
                due_date
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION["user_id"],
            $title,
            $description,
            $priority,
            $status,
            $dueDate ?: null
        ]);

        header("Location: dashboard.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Task</title>

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

        <h1>Add New Task</h1>

        <?php if ($message): ?>

            <div class="error">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <label>Task Title</label>

            <input
                type="text"
                name="title"
                maxlength="150"
                required
            >


            <label>Description</label>

            <textarea
                name="description"
                rows="5"
            ></textarea>


            <label>Priority</label>

            <select name="priority">

                <option value="Low">Low</option>

                <option value="Medium" selected>
                    Medium
                </option>

                <option value="High">High</option>

            </select>


            <label>Status</label>

            <select name="status">

                <option value="Pending">
                    Pending
                </option>

                <option value="In Progress">
                    In Progress
                </option>

                <option value="Completed">
                    Completed
                </option>

            </select>


            <label>Due Date</label>

            <input
                type="date"
                name="due_date"
            >


            <button type="submit">
                Create Task
            </button>

            <a
                href="dashboard.php"
                class="cancel-btn"
            >
                Cancel
            </a>

        </form>

    </div>

</main>

</body>
</html>

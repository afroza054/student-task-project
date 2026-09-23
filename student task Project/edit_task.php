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


// Get only the current user's task
$stmt = $pdo->prepare("
    SELECT *
    FROM tasks
    WHERE id = ? AND user_id = ?
");

$stmt->execute([
    $taskId,
    $_SESSION["user_id"]
]);

$task = $stmt->fetch();

if (!$task) {
    header("Location: dashboard.php");
    exit;
}


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
            UPDATE tasks

            SET
                title = ?,
                description = ?,
                priority = ?,
                status = ?,
                due_date = ?

            WHERE
                id = ?
                AND user_id = ?
        ");

        $stmt->execute([
            $title,
            $description,
            $priority,
            $status,
            $dueDate ?: null,
            $taskId,
            $_SESSION["user_id"]
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

    <title>Edit Task</title>

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

        <h1>Edit Task</h1>

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
                value="<?= htmlspecialchars($task["title"]) ?>"
                required
            >


            <label>Description</label>

            <textarea
                name="description"
                rows="5"
            ><?= htmlspecialchars(
                $task["description"] ?? ""
            ) ?></textarea>


            <label>Priority</label>

            <select name="priority">

                <option
                    value="Low"
                    <?= $task["priority"] === "Low"
                        ? "selected"
                        : "" ?>
                >
                    Low
                </option>

                <option
                    value="Medium"
                    <?= $task["priority"] === "Medium"
                        ? "selected"
                        : "" ?>
                >
                    Medium
                </option>

                <option
                    value="High"
                    <?= $task["priority"] === "High"
                        ? "selected"
                        : "" ?>
                >
                    High
                </option>

            </select>


            <label>Status</label>

            <select name="status">

                <option
                    value="Pending"
                    <?= $task["status"] === "Pending"
                        ? "selected"
                        : "" ?>
                >
                    Pending
                </option>

                <option
                    value="In Progress"
                    <?= $task["status"] === "In Progress"
                        ? "selected"
                        : "" ?>
                >
                    In Progress
                </option>

                <option
                    value="Completed"
                    <?= $task["status"] === "Completed"
                        ? "selected"
                        : "" ?>
                >
                    Completed
                </option>

            </select>


            <label>Due Date</label>

            <input
                type="date"
                name="due_date"
                value="<?= htmlspecialchars(
                    $task["due_date"] ?? ""
                ) ?>"
            >


            <button type="submit">
                Update Task
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

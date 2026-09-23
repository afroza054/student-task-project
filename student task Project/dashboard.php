<?php

require_once "includes/auth.php";
require_once "config/database.php";

$userId = $_SESSION["user_id"];
$userName = $_SESSION["user_name"];

$search = trim($_GET["search"] ?? "");
$status = $_GET["status"] ?? "";
$priority = $_GET["priority"] ?? "";

$sql = "
    SELECT id, title, description, priority, status, due_date, created_at
    FROM tasks
    WHERE user_id = ?
";

$params = [$userId];

if ($search !== "") {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (
    in_array(
        $status,
        ["Pending", "In Progress", "Completed"],
        true
    )
) {
    $sql .= " AND status = ?";
    $params[] = $status;
}

if (
    in_array(
        $priority,
        ["Low", "Medium", "High"],
        true
    )
) {
    $sql .= " AND priority = ?";
    $params[] = $priority;
}

$sql .= " ORDER BY due_date ASC, created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$tasks = $stmt->fetchAll();


// Statistics
$stmt = $pdo->prepare("
    SELECT
        COUNT(*) AS total,
        SUM(status = 'Pending') AS pending,
        SUM(status = 'In Progress') AS in_progress,
        SUM(status = 'Completed') AS completed
    FROM tasks
    WHERE user_id = ?
");

$stmt->execute([$userId]);

$stats = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Student Task Manager</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">
        Student Task Manager
    </div>

    <div class="nav-links">

        <a href="dashboard.php">Dashboard</a>

        <a href="profile.php">Profile</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>


<main class="dashboard">

    <div class="dashboard-header">

        <div>

            <h1>
                Welcome, <?= htmlspecialchars($userName) ?>
            </h1>

            <p>
                Manage and track your academic tasks.
            </p>

        </div>

        <a href="add_task.php" class="btn">
            + Add Task
        </a>

    </div>


    <!-- Statistics -->

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Tasks</h3>
            <strong>
                <?= (int)($stats["total"] ?? 0) ?>
            </strong>
        </div>

        <div class="stat-card">
            <h3>Pending</h3>
            <strong>
                <?= (int)($stats["pending"] ?? 0) ?>
            </strong>
        </div>

        <div class="stat-card">
            <h3>In Progress</h3>
            <strong>
                <?= (int)($stats["in_progress"] ?? 0) ?>
            </strong>
        </div>

        <div class="stat-card">
            <h3>Completed</h3>
            <strong>
                <?= (int)($stats["completed"] ?? 0) ?>
            </strong>
        </div>

    </div>


    <!-- Search and Filter -->

    <div class="filter-box">

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search tasks..."
                value="<?= htmlspecialchars($search) ?>"
            >

            <select name="status">

                <option value="">All Statuses</option>

                <option
                    value="Pending"
                    <?= $status === "Pending" ? "selected" : "" ?>
                >
                    Pending
                </option>

                <option
                    value="In Progress"
                    <?= $status === "In Progress" ? "selected" : "" ?>
                >
                    In Progress
                </option>

                <option
                    value="Completed"
                    <?= $status === "Completed" ? "selected" : "" ?>
                >
                    Completed
                </option>

            </select>


            <select name="priority">

                <option value="">All Priorities</option>

                <option
                    value="Low"
                    <?= $priority === "Low" ? "selected" : "" ?>
                >
                    Low
                </option>

                <option
                    value="Medium"
                    <?= $priority === "Medium" ? "selected" : "" ?>
                >
                    Medium
                </option>

                <option
                    value="High"
                    <?= $priority === "High" ? "selected" : "" ?>
                >
                    High
                </option>

            </select>

            <button type="submit">
                Search
            </button>

            <a href="dashboard.php" class="clear-btn">
                Clear
            </a>

        </form>

    </div>


    <!-- Task Table -->

    <div class="table-container">

        <?php if (count($tasks) > 0): ?>

            <table>

                <thead>

                    <tr>

                        <th>Task</th>

                        <th>Priority</th>

                        <th>Status</th>

                        <th>Due Date</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($tasks as $task): ?>

                    <tr>

                        <td>

                            <strong>
                                <?= htmlspecialchars($task["title"]) ?>
                            </strong>

                            <br>

                            <small>
                                <?= htmlspecialchars(
                                    $task["description"] ?? ""
                                ) ?>
                            </small>

                        </td>

                        <td>

                            <span class="badge priority-<?= strtolower(
                                $task["priority"]
                            ) ?>">

                                <?= htmlspecialchars(
                                    $task["priority"]
                                ) ?>

                            </span>

                        </td>

                        <td>

                            <span class="badge">

                                <?= htmlspecialchars(
                                    $task["status"]
                                ) ?>

                            </span>

                        </td>

                        <td>

                            <?= $task["due_date"]
                                ? htmlspecialchars($task["due_date"])
                                : "No date"
                            ?>

                        </td>

                        <td>

                            <a
                                href="edit_task.php?id=<?= (int)$task["id"] ?>"
                                class="edit-btn"
                            >
                                Edit
                            </a>

                            <a
                                href="delete_task.php?id=<?= (int)$task["id"] ?>"
                                class="delete-btn"
                                onclick="return confirm(
                                    'Are you sure you want to delete this task?'
                                );"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty-state">

                <h2>No tasks found</h2>

                <p>
                    Try changing your search/filter or create a new task.
                </p>

                <a href="add_task.php" class="btn">
                    Create Task
                </a>

            </div>

        <?php endif; ?>

    </div>

</main>

</body>
</html>

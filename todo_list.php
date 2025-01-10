<?php
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Add Task Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_description'])) {
    $task_description = $_POST['task_description'];

    // Insert the task into the tasks table
    $stmt = $conn->prepare("INSERT INTO tasks (task_description, created_by) VALUES (?, ?)");
    $stmt->bind_param('si', $task_description, $user_id);

    if ($stmt->execute()) {
        $message = "Task added successfully.";
    } else {
        $message = "Error adding task: " . $stmt->error;
    }

    $stmt->close();
}

// Mark Task as Completed Logic
if (isset($_GET['complete_task_id'])) {
    $task_id = $_GET['complete_task_id'];
    
    // Get the current timestamp
    $completed_at = date('Y-m-d H:i:s');

    // Update the task status to 'completed' and set completed_at
    $stmt = $conn->prepare("UPDATE tasks SET status = 'completed', completed_at = ? WHERE id = ?");
    $stmt->bind_param('si', $completed_at, $task_id);

    if ($stmt->execute()) {
        $message = "Task marked as completed.";
    } else {
        $message = "Error updating task: " . $stmt->error;
    }

    $stmt->close();
}

// Delete Tasks Completed More Than 1 Day Ago
$cleanup_sql = "DELETE FROM tasks WHERE status = 'completed' AND completed_at < DATE_SUB(NOW(), INTERVAL 1 DAY)";
$conn->query($cleanup_sql);

// Fetch All Tasks
$sql = "SELECT tasks.id, tasks.task_description, tasks.created_at, tasks.status, users.username 
        FROM tasks 
        JOIN users ON tasks.created_by = users.id
        ORDER BY tasks.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --text-color: #2c3e50;
            --border-color: #e9ecef;
            --bg-color: #f5f7fb;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        .header-nav {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .header-nav h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
        }

        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            animation: slideIn 0.5s ease-out;
        }

        .add-task {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: var(--bg-color);
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background-color: var(--bg-color);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: var(--warning-color);
            color: var(--text-color);
        }

        .status-completed {
            background-color: var(--success-color);
            color: white;
        }

        .edit-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .header-nav {
                padding: 1rem;
            }

            .table-responsive {
                margin: 0 -1rem;
            }
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="header-nav">
            <h1>To-Do List</h1>
        </div>

        <?php if ($message) { ?>
            <div class="alert alert-success message">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <div class="add-task">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="task_description" class="form-label">New Task</label>
                    <div class="input-group">
                        <input type="text" name="task_description" id="task_description" class="form-control" placeholder="Enter your task here" required>
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-plus me-2"></i>
                            Add Task
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($result->num_rows > 0) { ?>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Task Description</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['task_description']); ?></td>
                                    <td>
                                        <i class="fas fa-user me-2"></i>
                                        <?php echo htmlspecialchars($row['username']); ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>
                                        <?php echo $row['created_at']; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $row['status'] === 'pending' ? 'status-pending' : 'status-completed'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] === 'pending') { ?>
                                            <a href="?complete_task_id=<?php echo $row['id']; ?>" class="btn btn-dark edit-btn">
                                                <i class="fas fa-check me-2"></i>
                                                Complete
                                            </a>
                                        <?php } else { ?>
                                            <span class="text-muted">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Completed
                                            </span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <div class="text-center mt-5">
                <i class="fas fa-tasks fa-3x mb-3 text-muted"></i>
                <p class="text-muted">No tasks found. Add your first task above!</p>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
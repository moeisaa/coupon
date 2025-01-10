<?php
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = "";

// Function to check if username exists
function usernameExists($conn, $username, $excludeUserId = null) {
    $sql = "SELECT id FROM users WHERE username = ?" . ($excludeUserId ? " AND id != ?" : "");
    $stmt = $conn->prepare($sql);
    if ($excludeUserId) {
        $stmt->bind_param('si', $username, $excludeUserId);
    } else {
        $stmt->bind_param('s', $username);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Add User Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate input
        if (empty($username) || empty($password)) {
            $message = 'Username and password are required.';
        } elseif (usernameExists($conn, $username)) {
            $message = 'Username already exists.';
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $hashedPassword);

            if ($stmt->execute()) {
                $message = 'User created successfully!';
            } else {
                $message = 'Error creating the user. Please try again.';
            }

            $stmt->close();
        }
    } elseif ($action === 'edit') {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username)) {
            $message = 'Username is required.';
        } elseif (usernameExists($conn, $username, $user_id)) {
            $message = 'Username already exists.';
        } else {
            // Prepare the SQL statement for updating the user
            if (!empty($password)) {
                // Update password if provided
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssi', $username, $hashedPassword, $user_id);
            } else {
                // Update username only
                $sql = "UPDATE users SET username = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $username, $user_id);
            }

            if ($stmt->execute()) {
                $message = 'User updated successfully!';
            } else {
                $message = 'Error updating the user. Please try again.';
            }

            $stmt->close();
        }
    } elseif ($action === 'delete') {
        $user_id = $_POST['user_id'];

        // Delete the user from the database
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);

        if ($stmt->execute()) {
            $message = 'User deleted successfully!';
        } else {
            $message = 'Error deleting the user. Please try again.';
        }

        $stmt->close();
    }
}

// Fetch All Users
$sql = "SELECT id, username FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
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

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            text-align: center;
        }

        .header-section h1 {
            font-size: 2rem;
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

        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .form-section h2 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .input-group {
            margin-bottom: 1rem;
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

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-success {
            background-color: var(--success-color);
            border: none;
        }

        .btn-danger {
            background-color: var(--danger-color);
            border: none;
        }

        .users-table {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
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

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
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

            .form-section {
                padding: 1.5rem;
            }

            .users-table {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="dashboard-container">
            <div class="header-section">
                <h1>Users Management</h1>
            </div>

            <?php if ($message) { ?>
                <div class="message alert alert-<?php echo strpos($message, 'success') !== false ? 'success' : 'danger'; ?>">
                    <i class="fas <?php echo strpos($message, 'success') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <div class="form-section">
                <h2>Add New User</h2>
                <form method="POST" action="users_management.php">
                    <input type="hidden" name="action" value="add">
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create User
                    </button>
                </form>
            </div>

            <div class="form-section">
                <h2>Edit User</h2>
                <form method="POST" action="users_management.php">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="input-group">
                        <input type="text" name="username" id="edit_username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="Password (Leave empty to keep current)">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update User
                    </button>
                </form>
            </div>

            <div class="users-table">
                <h2>Existing Users</h2>
                <?php if ($result->num_rows > 0) { ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-user me-2"></i>
                                            <?php echo htmlspecialchars($row['username']); ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn btn-success" onclick="editUser(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['username']); ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" action="users_management.php" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="action-btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                        <p class="text-muted">No users found</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        function editUser(id, username) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_username').focus();
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>

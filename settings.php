<?php
// Include database connection
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Add default settings handler
if (isset($_POST['add_default_settings'])) {
    $defaults = [
        'site_logo' => 'static_pages/images/logo (1).svg',
        'sidebar_logo' => 'static_pages/images/logo (1).svg'
    ];
    
    foreach ($defaults as $key => $value) {
        $sql = "INSERT INTO settings (setting_key, setting_value) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $key, $value, $value);
        $stmt->execute();
    }
    
    header('Location: settings.php?success=1');
    exit();
}

// Check if default settings exist
$default_settings_exist = false;
$check_sql = "SELECT COUNT(*) as count FROM settings WHERE setting_key IN ('site_logo', 'sidebar_logo')";
$result = $conn->query($check_sql);
if ($result && $row = $result->fetch_assoc()) {
    $default_settings_exist = $row['count'] > 0;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle site logo upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $allowed = ['svg', 'png', 'jpg', 'jpeg'];
        $filename = $_FILES['site_logo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $upload_path = 'static_pages/images/';
            $new_filename = 'site_logo_' . time() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $upload_path . $new_filename)) {
                $sql = "INSERT INTO settings (setting_key, setting_value) 
                        VALUES ('site_logo', ?) 
                        ON DUPLICATE KEY UPDATE setting_value = ?";
                $stmt = $conn->prepare($sql);
                $path = $upload_path . $new_filename;
                $stmt->bind_param("ss", $path, $path);
                $stmt->execute();
            }
        }
    }

    // Handle sidebar logo upload
    if (isset($_FILES['sidebar_logo']) && $_FILES['sidebar_logo']['error'] == 0) {
        $allowed = ['svg', 'png', 'jpg', 'jpeg'];
        $filename = $_FILES['sidebar_logo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $upload_path = 'static_pages/images/';
            $new_filename = 'sidebar_logo_' . time() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['sidebar_logo']['tmp_name'], $upload_path . $new_filename)) {
                $sql = "INSERT INTO settings (setting_key, setting_value) 
                        VALUES ('sidebar_logo', ?) 
                        ON DUPLICATE KEY UPDATE setting_value = ?";
                $stmt = $conn->prepare($sql);
                $path = $upload_path . $new_filename;
                $stmt->bind_param("ss", $path, $path);
                $stmt->execute();
            }
        }
    }
    
    // Redirect to show success
    header('Location: settings.php?success=1');
    exit();
}

// Fetch current settings
$sql = "SELECT * FROM settings WHERE setting_key IN ('site_logo', 'sidebar_logo')";
$result = $conn->query($sql);
$settings = [];
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Include all the styles from your admin.php page here */
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f8f9fa;
            --text-color: #2c3e50;
            --border-color: #e9ecef;
        }

        body {
            background-color: #f5f7fb;
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

        .settings-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .settings-card h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .logo-preview {
            max-width: 200px;
            margin: 1rem 0;
            padding: 1rem;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
        }

        .logo-preview img {
            width: 100%;
            height: auto;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 0.5rem 1rem;
            cursor: pointer;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .custom-file-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="header-nav">
            <h1>Site Settings</h1>
        </div>

        <?php if (!$default_settings_exist): ?>
<form method="POST" class="mb-4">
    <button type="submit" name="add_default_settings" class="btn btn-success">
        <i class="fas fa-magic"></i> initial Settings
    </button>
</form>
<?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Settings updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="settings-card">
            <h2>Logo Settings</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">Site Logo</label>
                    <div class="logo-preview">
                        <img src="<?php echo htmlspecialchars($settings['site_logo'] ?? ''); ?>" alt="Current Site Logo">
                    </div>
                    <label class="custom-file-upload">
                        <input type="file" name="site_logo" accept=".svg,.png,.jpg,.jpeg" style="display: none;">
                        <i class="fas fa-upload"></i> Choose Site Logo
                    </label>
                    <small class="form-text text-muted d-block mt-2">Recommended: SVG, PNG, or JPG file</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Sidebar Logo</label>
                    <div class="logo-preview">
                        <img src="<?php echo htmlspecialchars($settings['sidebar_logo'] ?? ''); ?>" alt="Current Sidebar Logo">
                    </div>
                    <label class="custom-file-upload">
                        <input type="file" name="sidebar_logo" accept=".svg,.png,.jpg,.jpeg" style="display: none;">
                        <i class="fas fa-upload"></i> Choose Sidebar Logo
                    </label>
                    <small class="form-text text-muted d-block mt-2">Recommended: SVG, PNG, or JPG file</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview uploaded images before submission
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = this.closest('div').querySelector('.logo-preview img');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>
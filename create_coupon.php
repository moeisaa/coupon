<?php
include('db_connect.php');

// Get the page ID from the URL
$page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;

// Initialize default_coupon_url
$default_coupon_url = '';

// Check if page_id is valid
if ($page_id > 0) {
    // Get the default coupon URL for the given page
    $query = "SELECT default_coupon_url FROM pages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $page_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $stmt->close();

    if ($page) {
        $default_coupon_url = $page['default_coupon_url'] ?? '';
    } else {
        echo "Page not found.";
        exit;
    }
} else {
    echo "Invalid page ID.";
    exit;
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $type = $_POST['coupon_type'] ?? '';
    $code = ($type == 'code') ? ($_POST['code'] ?? '') : '';
    $url = $_POST['url'] ?? $default_coupon_url;
    $expire_date = $_POST['expire_date'] ?? null;
    $description = $_POST['description'] ?? 'Default description value';
    $details = $_POST['details'] ?? 'Default details value';
    $fake_initial_uses = intval($_POST['fake_initial_uses'] ?? 0);

    // Handle photo upload
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], 'static_pages/' . $photo);
    }

    // Insert coupon into the database
    $sql = "INSERT INTO coupons (page_id, title, type, code, url, photo, expire_date, description, details, fake_initial_uses, daily_uses) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssssssis', $page_id, $title, $type, $code, $url, $photo, $expire_date, $description, $details, $fake_initial_uses, $fake_initial_uses);

    if ($stmt->execute()) {
        header("Location: dashboard.php?page_id=" . htmlspecialchars($page_id));
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Show the form
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Coupon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f8f9fa;
            --success-color: #28a745;
            --border-color: #e9ecef;
            --text-color: #2c3e50;
            --input-bg: #ffffff;
            --hover-color: #f5f7fb;
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

        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: var(--text-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--input-bg);
            color: var(--text-color);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%232c3e50' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .file-input-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        input[type="file"] {
            display: none;
        }

        .file-input-label {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--secondary-color);
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background-color: var(--hover-color);
        }

        .file-input-label i {
            margin-right: 0.5rem;
        }

        .create-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.25);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .text-muted {
            color: #6c757d !important;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="form-container">
            <h1 class="text-center">Create New Coupon</h1>
            
            <form action="create_coupon.php?page_id=<?php echo $page_id; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="description">Title:</label>
                    <textarea id="description" name="description" placeholder="Enter coupon title"></textarea>
                </div>

                <div class="form-group">
                    <label for="title">Discount:</label>
                    <input type="text" id="title" name="title" required placeholder="Enter discount amount">
                </div>

                <div class="form-group">
                    <label for="coupon_type">Coupon Type:</label>
                    <select id="coupon_type" name="coupon_type" required onchange="updateFields()">
                        <option value="code">Code</option>
                        <option value="deal">Deal</option>
                    </select>
                </div>

                <div id="code_fields" class="form-group">
                    <label for="code">Coupon Code:</label>
                    <input type="text" id="code" name="code" placeholder="Enter coupon code">
                </div>

                <div class="form-group">
                    <label for="url">Coupon URL:</label>
                    <input type="text" id="url" name="url" value="<?php echo htmlspecialchars($default_coupon_url); ?>" placeholder="Enter coupon URL">
                </div>

                <div class="form-group">
                    <label for="photo" class="file-input-label">
                        <i class="fas fa-upload"></i>
                        Choose Photo
                    </label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <small class="selected-file-name"></small>
                </div>

                <div class="form-group">
                    <label for="expire_date">Expire Date:</label>
                    <input type="date" id="expire_date" name="expire_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                </div>

                <div class="form-group">
                    <label for="fake_initial_uses">Initial Usage Count:</label>
                    <input type="number" id="fake_initial_uses" name="fake_initial_uses" min="0" value="1" class="form-control">
                    <small class="text-muted">This number will show as initial daily uses count</small>
                </div>

                <div class="form-group">
                    <label for="details">Details:</label>
                    <textarea id="details" name="details" placeholder="Enter coupon details"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="create-btn">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Function to clean pasted text
    function cleanPastedText(text) {
        // Remove extra spaces and line breaks
        return text.replace(/\s+/g, ' ').trim();
    }

    // Function to update coupon type fields
    function updateFields() {
        var type = document.getElementById('coupon_type').value;
        document.getElementById('code_fields').style.display = (type === 'code') ? 'block' : 'none';
    }

    // Initialize the display based on the current type
    updateFields();

    // Add paste event handler for the title field
    document.getElementById('description').addEventListener('paste', function(e) {
        // Prevent the default paste
        e.preventDefault();
        
        // Get pasted text from clipboard
        let pastedText = (e.clipboardData || window.clipboardData).getData('text');
        
        // Clean the text
        let cleanedText = cleanPastedText(pastedText);
        
        // Insert cleaned text at cursor position
        document.execCommand('insertText', false, cleanedText);
    });

    // File input handling
    document.getElementById('photo').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'No file chosen';
        document.querySelector('.selected-file-name').textContent = fileName;
    });

    // Add validation for the fake initial uses input
    document.getElementById('fake_initial_uses').addEventListener('input', function(e) {
        const value = parseInt(e.target.value);
        if (value < 0) {
            e.target.value = 0;
        }
    });
</script>
</body>
</html>
<?php
}
?>
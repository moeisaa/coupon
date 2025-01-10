<?php
include('db_connect.php');

// Get coupon_id from query parameter
$coupon_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($coupon_id > 0) {
    // Fetch existing coupon details
    $query = "SELECT * FROM coupons WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $coupon_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $coupon = $result->fetch_assoc();
    $stmt->close();

    if (!$coupon) {
        echo "Coupon not found.";
        exit;
    }
} else {
    echo "Invalid coupon ID.";
    exit;
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $type = $_POST['coupon_type'] ?? '';
    $code = ($type == 'code') ? ($_POST['code'] ?? '') : '';
    $url = $_POST['url'] ?? $coupon['url'];
    $expire_date = $_POST['expire_date'] ?? '';
    $description = $_POST['description'] ?? 'Default description value';
    $details = $_POST['details'] ?? 'Default details value';
    $fake_initial_uses = intval($_POST['fake_initial_uses'] ?? 0);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update main coupon data
        $sql = "UPDATE coupons 
                SET title = ?, 
                    type = ?, 
                    code = ?, 
                    url = ?, 
                    expire_date = ?, 
                    description = ?, 
                    details = ?,
                    fake_initial_uses = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssii', $title, $type, $code, $url, $expire_date, $description, $details, $fake_initial_uses, $coupon_id);
        $stmt->execute();

        // If fake_initial_uses has changed, update daily_uses as well
        if ($fake_initial_uses != $coupon['fake_initial_uses']) {
            $sql = "UPDATE coupons 
                    SET daily_uses = ? 
                    WHERE id = ? 
                    AND (daily_uses IS NULL OR daily_uses = ?)";
            $stmt = $conn->prepare($sql);
            $old_fake_uses = $coupon['fake_initial_uses'];
            $stmt->bind_param('iii', $fake_initial_uses, $coupon_id, $old_fake_uses);
            $stmt->execute();
        }

        $conn->commit();
        header("Location: dashboard.php?page_id=" . htmlspecialchars($coupon['page_id']));
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
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
    <title>Edit Coupon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f8f9fa;
            --text-color: #2c3e50;
            --border-color: #e9ecef;
            --input-bg: #ffffff;
            --hover-color: #f5f7fb;
            --success-color: #28a745;
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
            padding: 2.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .page-title {
            color: var(--text-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
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

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: auto;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.25);
        }

        .submit-btn i {
            margin-right: 0.5rem;
        }

        #code_fields {
            transition: all 0.3s ease;
        }

        .text-muted {
            color: #6c757d !important;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Custom styles for number input */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .submit-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="form-container">
            <h1 class="page-title">Edit Coupon</h1>
            
            <form action="edit_coupon.php?id=<?php echo htmlspecialchars($coupon_id); ?>" method="post">
                <div class="form-group">
                    <label for="description">Title:</label>
                    <textarea id="description" name="description" placeholder="Enter coupon title"><?php echo htmlspecialchars($coupon['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="title">Discount:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($coupon['title'] ?? ''); ?>" placeholder="Enter discount amount">
                </div>

                <div class="form-group">
                    <label for="coupon_type">Coupon Type:</label>
                    <select id="coupon_type" name="coupon_type" onchange="updateFields()">
                        <option value="code" <?php echo ($coupon['type'] ?? '') == 'code' ? 'selected' : ''; ?>>Code</option>
                        <option value="deal" <?php echo ($coupon['type'] ?? '') == 'deal' ? 'selected' : ''; ?>>Deal</option>
                    </select>
                </div>

                <div id="code_fields" class="form-group" style="display: <?php echo ($coupon['type'] ?? '') == 'code' ? 'block' : 'none'; ?>;">
                    <label for="code">Coupon Code:</label>
                    <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($coupon['code'] ?? ''); ?>" placeholder="Enter coupon code">
                </div>

                <div class="form-group">
                    <label for="url">Coupon URL:</label>
                    <input type="text" id="url" name="url" value="<?php echo htmlspecialchars($coupon['url'] ?? ''); ?>" placeholder="Enter coupon URL">
                </div>

                <div class="form-group">
                    <label for="expire_date">Expire Date:</label>
                    <input type="date" id="expire_date" name="expire_date" value="<?php echo htmlspecialchars($coupon['expire_date'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="fake_initial_uses">Initial Usage Count:</label>
                    <input type="number" 
                           id="fake_initial_uses" 
                           name="fake_initial_uses" 
                           min="0" 
                           value="<?php echo htmlspecialchars($coupon['fake_initial_uses'] ?? 0); ?>" 
                           class="form-control">
                    <small class="text-muted">This number will show as initial daily uses count</small>
                </div>

                <div class="form-group">
                    <label for="details">Details:</label>
                    <textarea id="details" name="details" placeholder="Enter coupon details"><?php echo htmlspecialchars($coupon['details'] ?? ''); ?></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i>
                        Save Changes
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
        const couponType = document.getElementById('coupon_type').value;
        const codeFields = document.getElementById('code_fields');
        
        if (couponType === 'code') {
            codeFields.style.display = 'block';
            codeFields.style.opacity = '1';
        } else {
            codeFields.style.opacity = '0';
            setTimeout(() => {
                codeFields.style.display = 'none';
            }, 300);
        }
    }

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
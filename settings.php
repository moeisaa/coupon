<?php

// Include database connection
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Read and parse .env file
$env = parse_ini_file(__DIR__ . '/.env');

//  Footer 
// Handle footer creation/update
if (isset($_POST['save_footer'])) {
    $name = $conn->real_escape_string($_POST['footer_name']);
    $footer_links = json_encode($_POST['footer_links']);
    $footer_note = $conn->real_escape_string($_POST['footer_note']);
    $copyright_text = $conn->real_escape_string($_POST['copyright_text']);
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    if ($is_default) {
        // Reset all other footers to non-default
        $conn->query("UPDATE footers SET is_default = 0");
    }
    
    if (isset($_POST['footer_id'])) {
        // Update existing footer
        $id = intval($_POST['footer_id']);
        $sql = "UPDATE footers SET 
                name = ?, 
                footer_links = ?,
                footer_note = ?,
                copyright_text = ?,
                is_default = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $name, $footer_links, $footer_note, $copyright_text, $is_default, $id);
    } else {
        // Create new footer
        $sql = "INSERT INTO footers (name, footer_links, footer_note, copyright_text, is_default) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $footer_links, $footer_note, $copyright_text, $is_default);
    }
    
    $stmt->execute();
    header('Location: settings.php?success=1');
    exit();
}

// Get all footers
$footers = $conn->query("SELECT * FROM footers ORDER BY is_default DESC, name ASC");




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



            <!-- Footer Mangement -->
<div class="settings-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Footer Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#footerModal">
            <i class="fas fa-plus"></i> New Footer
        </button>
    </div>
    
    <div class="table-responsive">
    <table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Background Color</th>
            <th>Default</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($footer = $footers->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($footer['name']); ?></td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="color-preview me-2" 
                         style="width: 20px; height: 20px; border: 1px solid #ccc; 
                                background-color: <?php echo htmlspecialchars($footer['background_color'] ?? '#FFFFFF'); ?>">
                    </div>
                    <?php echo htmlspecialchars($footer['background_color'] ?? '#FFFFFF'); ?>
                </div>
            </td>
            <td>
                <?php if ($footer['is_default']): ?>
                    <span class="badge bg-success">Default</span>
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-primary edit-footer" 
                        data-footer='<?php echo htmlspecialchars(json_encode($footer)); ?>'>
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-footer" 
                        data-id="<?php echo $footer['id']; ?>">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    </div>
</div>

    </div>



<!-- Footer Modal -->
<div class="modal fade" id="footerModal" tabindex="-1" aria-labelledby="footerModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form id="footerForm" method="POST" action="services/save_footer.php">
        <script>
        const footerModal = document.getElementById('footerModal');
        footerModal.addEventListener('hidden.bs.modal', function (event) {
            resetFooterForm();
        });
    </script>
<div class="modal-header">
                    <h5 class="modal-title">Footer Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
    <input type="hidden" name="footer_id" id="footer_id">
    
    <div class="mb-3">
        <label class="form-label">Footer Name</label>
        <input type="text" class="form-control" name="footer_name" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Background Color</label>
        <div class="input-group">
            <input type="color" class="form-control form-control-color" 
                   name="background_color" id="background_color" 
                   title="Choose background color" value="#FFFFFF">
            <input type="text" class="form-control" id="background_color_text" 
                   placeholder="#FFFFFF" pattern="^#[0-9A-Fa-f]{6}$">
        </div>
        <small class="form-text text-muted">Choose a background color for the footer</small>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Footer Links</label>
        <div id="footer-links-container">
            <!-- Footer links content remains the same -->
        </div>
        <button type="button" class="btn btn-secondary mt-2" id="add-footer-link">
            <i class="fas fa-plus"></i> Add Link
        </button>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Footer Note</label>
        <textarea class="form-control" name="footer_note" rows="3"></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Copyright Text</label>
        <input type="text" class="form-control" name="copyright_text">
    </div>
    
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="is_default" id="is_default">
            <label class="form-check-label" for="is_default">Set as Default Footer</label>
        </div>
    </div>
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_footer" class="btn btn-primary">Save Footer</button>
                </div>
            </form>
        </div>
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


<script>
function resetFooterForm() {
    // Reset the form fields
    document.getElementById('footer_id').value = '';
    document.querySelector('[name="footer_name"]').value = '';
    document.querySelector('[name="footer_note"]').value = '';
    document.querySelector('[name="copyright_text"]').value = '';
    document.querySelector('[name="background_color"]').value = '#FFFFFF';
    document.getElementById('background_color_text').value = '#FFFFFF';
    document.getElementById('is_default').checked = false;
    
    // Clear existing links
    const linksContainer = document.getElementById('footer-links-container');
    linksContainer.innerHTML = '';
    
    // Add 3 default links with example URLs
    const defaultLinks = [
    { text: 'Terms and Conditions', url: '<?php echo $env['DOMAIN']; ?>/info/terms' },
    { text: 'Privacy Policy', url: '<?php echo $env['DOMAIN']; ?>/info/privacy' },
    { text: 'Contact Us', url: '<?php echo $env['DOMAIN']; ?>/info/contact' }
];
    
    // Add the default links
    defaultLinks.forEach(link => {
        addFooterLinkGroup(link.text, link.url);
    });
}



document.addEventListener('DOMContentLoaded', function() {
    // Sync color input and text input
    const colorPicker = document.getElementById('background_color');
    const colorText = document.getElementById('background_color_text');

    colorPicker.addEventListener('input', function(e) {
        colorText.value = e.target.value.toUpperCase();
    });

    colorText.addEventListener('input', function(e) {
        if (e.target.value.match(/^#[0-9A-Fa-f]{6}$/)) {
            colorPicker.value = e.target.value;
        }
    });
    document.querySelector('[data-bs-target="#footerModal"]').addEventListener('click', function() {
        resetFooterForm();
});

// Edit footer
document.querySelectorAll('.edit-footer').forEach(btn => {
    btn.addEventListener('click', function() {
            const footer = JSON.parse(this.dataset.footer);
            const modal = new bootstrap.Modal(document.getElementById('footerModal'));
            
            document.getElementById('footer_id').value = footer.id;
            document.querySelector('[name="footer_name"]').value = footer.name;
            document.querySelector('[name="footer_note"]').value = footer.footer_note;
            document.querySelector('[name="copyright_text"]').value = footer.copyright_text;
            document.getElementById('is_default').checked = footer.is_default == 1;
            document.querySelector('[name="background_color"]').value = footer.background_color || '#FFFFFF';
            document.getElementById('background_color_text').value = footer.background_color || '#FFFFFF';
            
            // Clear existing links first
            const linksContainer = document.getElementById('footer-links-container');
            linksContainer.innerHTML = '';
            
            // Add footer links if they exist
            const links = JSON.parse(footer.footer_links || '[]');
            if (links.length > 0) {
                links.forEach(link => {
                    addFooterLinkGroup(link.text, link.url);
                });
            } else {
                // If no links, add one empty link group
                addFooterLinkGroup();
            }
            
            modal.show();
        });
    });
});

// Delete footer
document.querySelectorAll('.delete-footer').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this footer?')) {
            const id = this.dataset.id;
            fetch(`services/delete_footer.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting footer');
                    }
                });
        }
    });
});

function addFooterLinkGroup(text = '', url = '') {
    const container = document.getElementById('footer-links-container');
    const index = container.children.length;
    
    const group = document.createElement('div');
    group.className = 'footer-link-group mb-2';
    group.innerHTML = `
        <div class="row">
            <div class="col-5">
                <input type="text" name="footer_links[${index}][text]" 
                       class="form-control" placeholder="Link Text" value="${text}">
            </div>
            <div class="col-5">
                <input type="text" name="footer_links[${index}][url]" 
                       class="form-control" placeholder="Link URL" value="${url}">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger remove-link">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(group);
}

document.getElementById('add-footer-link').addEventListener('click', () => {
    addFooterLinkGroup();
});

document.getElementById('footer-links-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-link')) {
        e.target.closest('.footer-link-group').remove();
    }
});
// Update in your existing edit-footer event listener
document.querySelector('[name="background_color"]').value = footer.background_color || '#FFFFFF';
document.getElementById('background_color_text').value = footer.background_color || '#FFFFFF';
</script>
</body>
</html>
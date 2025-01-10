<?php
include('db_connect.php');
//  Hello Test Commit
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM pages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Invalid page ID.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --text-color: #2c3e50;
            --border-color: #e9ecef;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --hover-color: #f5f7fb;
            --shadow-color: rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 8px 30px var(--shadow-color);
            padding: 2.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
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
            background-color: var(--hover-color);
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background-color: var(--border-color);
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
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.25);
        }

        .availability-check {
            position: absolute;
            right: 0.5rem;
            top: 2.8rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%232c3e50' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
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
        /* Add these styles inside your existing <style> tag */
.theme-cards {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.theme-card {
    flex: 1;
    min-width: 200px;
    max-width: 250px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.theme-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.theme-card.active {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

.theme-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 0.5rem;
}

.theme-card span {
    display: block;
    text-align: center;
    font-weight: 500;
    color: var(--text-color);
    font-size: 0.9rem;
}

.theme-preview {
    margin-top: 2rem;
}
.editor-toolbar {
    display: flex;
    gap: 5px;
    padding: 10px;
    background: #f8f9fa;
    border: 1px solid var(--border-color);
    border-bottom: none;
    border-radius: 8px 8px 0 0;
}

.format-btn {
    padding: 6px 12px;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.format-btn:hover {
    background: #e9ecef;
}

.format-btn.active {
    background: var(--primary-color);
    color: white;
}

.separator {
    margin: 0 8px;
    color: var(--border-color);
}

.editor-content {
    min-height: 200px;
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 0 0 8px 8px;
    background: white;
    overflow-y: auto;
}

.editor-content:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

.text-large { font-size: 1.5em; }
.text-normal { font-size: 1em; }
.text-small { font-size: 0.875em; }
.spacing-1 { line-height: 1.5; }
.spacing-2 { line-height: 2; }

    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div id="main-content">
        <div class="form-container">
            <h1 class="page-title">Edit Page</h1>
            
            <form action="update_page.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($page['id']); ?>">
                
                <div class="form-group">
                    <label for="route">Route:</label>
                    <input type="text" id="route" name="route" value="<?php echo htmlspecialchars($page['route']); ?>" placeholder="Enter route">
                    <div id="route-check" class="availability-check"></div>
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <input type="number" id="rating" name="rating" value="<?php echo htmlspecialchars($page['rating']); ?>" placeholder="Enter rating" min="0" max="5" step="0.1">
                </div>

                
                <div class="form-group">
    <label for="votes">Number of Votes:</label>
    <input type="number" id="votes" name="votes" value="<?php echo htmlspecialchars($page['votes']); ?>" placeholder="Enter number of votes" min="0">
</div>

                <div class="form-group">
                    <label for="header">Header:</label>
                    <input type="text" id="header" name="header" value="<?php echo htmlspecialchars($page['header']); ?>" placeholder="Enter header text">
                </div>
                
                <textarea id="description" name="description" hidden>AUTO Description</textarea>
                
                <div class="form-group">
    <label for="blog">Blog Content:</label>
    <div class="editor-toolbar">
        <button type="button" class="format-btn" data-size="large" title="Large Text">
            <i class="fas fa-heading"></i>
        </button>
        <button type="button" class="format-btn" data-size="normal" title="Normal Text">
            <i class="fas fa-text-height"></i>
        </button>
        <button type="button" class="format-btn" data-size="small" title="Small Text">
            <i class="fas fa-text-width"></i>
        </button>
        <span class="separator">|</span>
        <button type="button" class="format-btn" data-action="bold" title="Bold">
            <i class="fas fa-bold"></i>
        </button>
        <button type="button" class="format-btn" data-action="italic" title="Italic">
            <i class="fas fa-italic"></i>
        </button>
        <span class="separator">|</span>
        <button type="button" class="format-btn" data-spacing="1" title="Single Space">
            <i class="fas fa-text-height"></i>1
        </button>
        <button type="button" class="format-btn" data-spacing="2" title="Double Space">
            <i class="fas fa-text-height"></i>2
        </button>
    </div>
    <div id="blog-editor" class="editor-content" contenteditable="true"><?php echo htmlspecialchars_decode($page['blog']); ?></div>
    <textarea id="blog" name="blog" style="position: absolute; left: -9999px;" required><?php echo htmlspecialchars($page['blog']); ?></textarea>
</div>

                
                <div class="form-group">
                    <label for="logo" class="file-input-label">
                        <i class="fas fa-upload me-2"></i>
                        Choose Logo File
                    </label>
                    <input type="file" id="logo" name="logo" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="store_name">Store Name:</label>
                    <input type="text" id="store_name" name="store_name" value="<?php echo htmlspecialchars($page['store_name']); ?>" placeholder="Enter store name">
                </div>
                
                <div class="form-group">
                    <label for="default_coupon_url">Default Coupon URL:</label>
                    <input type="text" id="default_coupon_url" name="default_coupon_url" value="<?php echo htmlspecialchars($page['default_coupon_url']); ?>" placeholder="Enter default coupon URL">
                </div>
                <div class="form-group">
    <label for="language">Language:</label>
    <select id="language_id" name="language_id" required>
        <?php
        $lang_query = "SELECT id, name FROM languages WHERE is_active = 1";
        $lang_result = $conn->query($lang_query);
        while ($lang = $lang_result->fetch_assoc()) {
            $selected = ($lang['id'] == $page['language_id']) ? 'selected' : '';
            echo "<option value='{$lang['id']}' {$selected}>{$lang['name']}</option>";
        }
        ?>
    </select>
</div>
                
                <div class="form-group">
                    <label for="text_direction">Text Direction:</label>
                    <select id="text_direction" name="text_direction">
                        <option value="ltr" <?php echo $page['text_direction'] == 'ltr' ? 'selected' : ''; ?>>Left to Right (LTR)</option>
                        <option value="rtl" <?php echo $page['text_direction'] == 'rtl' ? 'selected' : ''; ?>>Right to Left (RTL)</option>
                    </select>
                </div>

                <div class="form-group">
    <label for="theme">Theme:</label>
    <select id="theme" name="theme">
        <option value="theme1" <?php echo ($page['theme'] == 'theme1' || empty($page['theme'])) ? 'selected' : ''; ?>>Theme 1 (Classic)</option>
        <option value="theme2" <?php echo $page['theme'] == 'theme2' ? 'selected' : ''; ?>>Theme 2 (Modern)</option>
    </select>
</div>
<!-- Update the theme2-options div -->
<div class="theme2-options" style="display: none;">
    <div class="form-group mt-4">
        <label class="mb-2">Theme Color</label>
        <div class="color-selector-container">
            <div class="color-options">
                <label class="color-option">
                    <input type="radio" name="theme_color" value="blue" <?php echo ($page['theme_color'] == 'blue' || !$page['theme_color']) ? 'checked' : ''; ?>>
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #2563eb"></span>
                        <span class="color-label">Blue</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="purple" <?php echo $page['theme_color'] == 'purple' ? 'checked' : ''; ?>>
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #7c3aed"></span>
                        <span class="color-label">Purple</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="green" <?php echo $page['theme_color'] == 'green' ? 'checked' : ''; ?>>
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #059669"></span>
                        <span class="color-label">Green</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="red" <?php echo $page['theme_color'] == 'red' ? 'checked' : ''; ?>>
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #dc2626"></span>
                        <span class="color-label">Red</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="custom" <?php echo $page['theme_color'] == 'custom' ? 'checked' : ''; ?>>
                    <div class="color-option-inner">
                        <span class="color-preview custom-preview" style="background: <?php echo $page['custom_color'] ?? 'linear-gradient(45deg, #ff0000, #00ff00, #0000ff)'; ?>"></span>
                        <span class="color-label">Custom</span>
                    </div>
                </label>
            </div>

            <input type="color" id="custom_color" name="custom_color" 
                   value="<?php echo $page['custom_color'] ?? '#000000'; ?>" 
                   class="custom-color-input" style="display: none;">
        </div>
    </div>
</div>

<style>
/* Updated responsive styles */
.color-selector-container {
    width: 100%;
    margin-top: 0.5rem;
}

.color-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 10px;
    width: 100%;
}

.color-option {
    cursor: pointer;
}

.color-option-inner {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    transition: all 0.2s ease;
}

.color-option input[type="radio"] {
    display: none;
}

.color-option input[type="radio"]:checked + .color-option-inner {
    background: #f8f9fa;
    border-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.color-preview {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid var(--border-color);
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.color-option input[type="radio"]:checked + .color-option-inner .color-preview {
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.color-label {
    font-size: 0.9rem;
    color: var(--text-color);
    white-space: nowrap;
}

.custom-color-input {
    width: 100%;
    max-width: 60px;
    height: 40px;
    padding: 2px;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    margin-top: 10px;
}

/* Media Queries */
@media (max-width: 768px) {
    .color-options {
        grid-template-columns: repeat(2, 1fr);
    }

    .color-option-inner {
        padding: 8px;
    }
}

@media (max-width: 480px) {
    .color-options {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .color-option-inner {
        padding: 6px;
    }

    .color-preview {
        width: 20px;
        height: 20px;
    }

    .color-label {
        font-size: 0.8rem;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeSelect = document.getElementById('theme');
    const theme2Options = document.querySelector('.theme2-options');
    const colorRadios = document.querySelectorAll('input[name="theme_color"]');
    const customColorInput = document.getElementById('custom_color');

    function toggleTheme2Options() {
        theme2Options.style.display = themeSelect.value === 'theme2' ? 'block' : 'none';
    }

    themeSelect.addEventListener('change', toggleTheme2Options);
    toggleTheme2Options();

    colorRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            customColorInput.style.display = this.value === 'custom' ? 'block' : 'none';
            if (this.value === 'custom') {
                document.querySelector('.custom-preview').style.background = customColorInput.value;
            }
        });
    });

    customColorInput.addEventListener('input', function() {
        document.querySelector('.custom-preview').style.background = this.value;
    });
});
</script>

                <div class="text-center mt-4">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save me-2"></i>
                        Update Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input handling
        document.getElementById('logo').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Choose Logo File';
            this.previousElementSibling.innerHTML = `<i class="fas fa-file-image me-2"></i>${fileName}`;
        });

        // Route availability checker
        const routeInput = document.getElementById('route');
        const routeCheckDiv = document.getElementById('route-check');
        let checkTimeout;

        routeInput.addEventListener('input', () => {
            clearTimeout(checkTimeout);
            const route = routeInput.value;

            if (route.length > 0) {
                checkTimeout = setTimeout(() => {
                    fetch(`check_route.php?route=${encodeURIComponent(route)}`)
                        .then(response => response.text())
                        .then(data => {
                            if (data.includes('unavailable')) {
                                routeCheckDiv.innerHTML = "<i class='fas fa-times-circle'></i><span class='text-danger'>Route unavailable</span>";
                            } else {
                                routeCheckDiv.innerHTML = "<i class='fas fa-check-circle'></i><span class='text-success'>Route available</span>";
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            routeCheckDiv.innerHTML = "<i class='fas fa-exclamation-circle'></i><span class='text-danger'>Error checking route</span>";
                        });
                }, 500);
            } else {
                routeCheckDiv.innerHTML = "";
            }
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('blog-editor');
    const textarea = document.getElementById('blog');
    const formatBtns = document.querySelectorAll('.format-btn');
    const form = document.querySelector('form');

    // Initialize editor with textarea content
    if (textarea.value) {
        // Content is already loaded in the editor div from PHP
        console.log('Editor initialized with existing content');
    }

    // Update hidden textarea before form submission
    form.addEventListener('submit', function(e) {
        if (editor.innerHTML.trim() === '') {
            e.preventDefault();
            alert('Please enter blog content');
            editor.focus();
            return false;
        }
        textarea.value = editor.innerHTML;
    });

    // Format buttons click handlers
    formatBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const size = this.dataset.size;
            const action = this.dataset.action;
            const spacing = this.dataset.spacing;
            
            if (size) {
                document.execCommand('formatBlock', false, 'div');
                const selection = window.getSelection();
                if (!selection.isCollapsed) {
                    const range = selection.getRangeAt(0);
                    const span = document.createElement('span');
                    span.className = `text-${size}`;
                    range.surroundContents(span);
                }
            }
            
            if (action === 'bold') {
                document.execCommand('bold', false, null);
            }
            
            if (action === 'italic') {
                document.execCommand('italic', false, null);
            }
            
            if (spacing) {
                const selection = window.getSelection();
                if (!selection.isCollapsed) {
                    const range = selection.getRangeAt(0);
                    const span = document.createElement('span');
                    span.className = `spacing-${spacing}`;
                    range.surroundContents(span);
                }
            }
            
            editor.focus();
        });
    });

    // Ensure content is copied to textarea when typing in editor
    editor.addEventListener('input', function() {
        textarea.value = this.innerHTML;
    });
});
</script>
</body>
</html>
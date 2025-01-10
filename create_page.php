<?php include('db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Static Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add this to your existing CSS */
        .theme-selector {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .theme-option {
            flex: 1;
            max-width: 300px;
        }

        .theme-option input[type="radio"] {
            display: none;
        }

        .theme-label {
            display: block;
            padding: 10px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .theme-label img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .theme-label span {
            display: block;
            text-align: center;
            font-weight: 500;
        }

        .theme-option input[type="radio"]:checked+.theme-label {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }


        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f8f9fa;
            --text-color: #2c3e50;
            --border-color: #e9ecef;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --heading-color: #34495e;
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
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--heading-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .section-title {
            font-size: 1.4rem;
            color: var(--heading-color);
            margin: 2rem 0 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
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
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        textarea {
            min-height: 150px;
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
            background-color: var(--secondary-color);
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background-color: var(--border-color);
        }

        .route-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .generate-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .generate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.25);
        }

        .route-status {
            position: absolute;
            right: 1rem;
            top: 2.8rem;
            font-size: 0.9rem;
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
            <h1 class="page-title">Create Your Static Page</h1>

            <form action="save_page.php" method="POST" enctype="multipart/form-data">
                <div class="section-title">Basic Information</div>

                <div class="form-group">
                    <label for="logo" class="file-input-label">
                        <i class="fas fa-upload me-2"></i>
                        <span>Choose Logo File</span>
                    </label>
                    <input type="file" id="logo" name="logo" required accept="image/*">
                </div>

                <div class="form-group">
                    <label for="rating">Rating (1-5):</label>
                    <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" required
                        placeholder="Enter rating">
                </div>

                <!-- New votes input field -->
                <div class="form-group">
                    <label for="votes">Number of Votes:</label>
                    <input type="number" id="votes" name="votes" min="0" value="6665"required placeholder="Enter number of votes"
                        class="form-control">
                </div>

                <div class="route-container">
                    <label for="route">Custom Route:</label>
                    <input type="text" id="route" name="route" placeholder="Leave empty for random"
                        onkeyup="checkRoute()">
                    <span id="route_status" class="route-status"></span>
                    <button type="button" class="generate-btn" onclick="generateRandomRoute()">
                        <i class="fas fa-random me-2"></i>Generate Random Route
                    </button>
                </div>

                <div class="section-title">Content</div>

                <div class="form-group">
                    <label for="header">Header:</label>
                    <input type="text" id="header" name="header" required placeholder="Enter page header">
                </div>

                <textarea name="description" hidden>AUTO Description</textarea>


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
    <div id="blog-editor" class="editor-content" contenteditable="true"></div>
    <textarea id="blog" name="blog" style="position: absolute; left: -9999px;" required></textarea>
</div>

                <div class="section-title">Page Details</div>

                <div class="form-group">
                    <label for="store_name">Store Name:</label>
                    <input type="text" id="store_name" name="store_name" required placeholder="Enter store name">
                </div>

                <div class="form-group">
                    <label for="default_coupon_url">Default Coupon URL:</label>
                    <input type="text" id="default_coupon_url" name="default_coupon_url"
                        placeholder="Enter default URL for coupons">
                </div>

                <div class="form-group">
    <label for="language_id">Language:</label>
    <select id="language_id" name="language_id" required>
        <?php
        $lang_query = "SELECT id, name FROM languages WHERE is_active = 1";
        $lang_result = $conn->query($lang_query);
        while ($lang = $lang_result->fetch_assoc()) {
            echo "<option value='{$lang['id']}'>{$lang['name']}</option>";
        }
        ?>
    </select>
</div>

                <div class="form-group">
                    <label for="text_direction">Direction:</label>
                    <select id="text_direction" name="text_direction">
                        <option value="ltr" selected>Left to Right (LTR)</option>
                        <option value="rtl">Right to Left (RTL)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="theme">Select Theme:</label>
                    <div class="theme-selector">
                        <div class="theme-option">
                            <input type="radio" id="theme1" name="theme" value="theme1" checked>
                            <label for="theme1" class="theme-label">
                                <img src="classic-theme.png" alt="Theme 1 Preview">
                                <span>Classic Theme</span>
                            </label>
                        </div>
                        <div class="theme-option">
                            <input type="radio" id="theme2" name="theme" value="theme2">
                            <label for="theme2" class="theme-label">
                                <img src="modern-theme.png" alt="Theme 2 Preview">
                                <span>Modern Theme</span>
                            </label>
                        </div>
                    </div>
                </div>
     <!-- Update the theme color section HTML -->
<div class="theme2-options" style="display: none;">
    <div class="form-group mt-4">
        <label class="mb-2">Theme Color</label>
        <div class="color-selector-container">
            <!-- Preset color options -->
            <div class="color-options">
                <label class="color-option">
                    <input type="radio" name="theme_color" value="blue" checked>
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #2563eb"></span>
                        <span class="color-label">Blue</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="purple">
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #7c3aed"></span>
                        <span class="color-label">Purple</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="green">
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #059669"></span>
                        <span class="color-label">Green</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="red">
                    <div class="color-option-inner">
                        <span class="color-preview" style="background: #dc2626"></span>
                        <span class="color-label">Red</span>
                    </div>
                </label>

                <label class="color-option">
                    <input type="radio" name="theme_color" value="custom">
                    <div class="color-option-inner">
                        <span class="color-preview custom-preview"></span>
                        <span class="color-label">Custom</span>
                    </div>
                </label>
            </div>

            <!-- Custom color picker -->
            <input type="color" id="custom_color" name="custom_color" class="custom-color-input" style="display: none;">
        </div>
    </div>
</div>

<style>
/* Updated CSS for better mobile responsiveness */
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
    position: relative;
    cursor: pointer;
}

.color-option-inner {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.color-option input[type="radio"] {
    display: none;
}

.color-option input[type="radio"]:checked + .color-option-inner {
    background: #f8f9fa;
    border-color: #4a90e2;
}

.color-preview {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.color-option input[type="radio"]:checked + .color-option-inner .color-preview {
    border-color: #4a90e2;
    transform: scale(1.1);
}

.custom-preview {
    background: linear-gradient(45deg, #ff0000, #00ff00, #0000ff);
}

.color-label {
    font-size: 0.9rem;
    color: #2c3e50;
    white-space: nowrap;
}

.custom-color-input {
    width: 100%;
    max-width: 60px;
    height: 40px;
    padding: 2px;
    border-radius: 4px;
    border: 1px solid #e9ecef;
    margin-top: 10px;
}

/* Media Queries */
@media (max-width: 768px) {
    .color-options {
        grid-template-columns: repeat(2, 1fr);
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

    .color-label {
        font-size: 0.8rem;
    }

    .color-preview {
        width: 20px;
        height: 20px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeRadios = document.querySelectorAll('input[name="theme"]');
    const theme2Options = document.querySelector('.theme2-options');
    const colorRadios = document.querySelectorAll('input[name="theme_color"]');
    const customColorInput = document.getElementById('custom_color');
    
    // Show/hide theme2 options based on theme selection
    themeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            theme2Options.style.display = this.value === 'theme2' ? 'block' : 'none';
        });
    });

    // Show/hide custom color input based on color selection
    colorRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            customColorInput.style.display = this.value === 'custom' ? 'block' : 'none';
        });
    });

    // Update custom preview when color is changed
    customColorInput.addEventListener('input', function() {
        document.querySelector('.custom-preview').style.background = this.value;
    });
});
</script>


<!-- Footer Setting  -->
<div class="section-title">Footer Settings</div>

<div class="form-group">
    <label for="footer_links">Footer Links:</label>
    <div id="footer-links-container">
        <div class="footer-link-group mb-3">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="footer_links[0][text]" class="form-control mb-2" placeholder="Link Text">
                </div>
                <div class="col-md-5">
                    <input type="text" name="footer_links[0][url]" class="form-control mb-2" placeholder="Link URL">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-link" style="display: none;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-secondary mt-2" id="add-footer-link">
        <i class="fas fa-plus me-2"></i>Add Footer Link
    </button>
</div>

<div class="form-group">
    <label for="copyright_text">Copyright Text:</label>
    <input type="text" id="copyright_text" name="copyright_text" placeholder="Enter copyright text" class="form-control">
</div>
<div class="form-group">
    <label for="footer_note">Footer Note:</label>
    <textarea id="footer_note" name="footer_note" placeholder="Enter footer note" class="form-control" rows="3"></textarea>
</div>
<style>

.footer-note {
    padding: 1rem;
    color: var(--text-color);
    opacity: 0.8;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 1rem 0;
}
.footer-link-group {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.remove-link {
    padding: 0.375rem 0.75rem;
}

#add-footer-link {
    background: var(--secondary-color);
    color: var(--text-color);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

#add-footer-link:hover {
    background: var(--border-color);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('footer-links-container');
    const addButton = document.getElementById('add-footer-link');
    let linkCount = 1;

    addButton.addEventListener('click', function() {
        const newGroup = document.createElement('div');
        newGroup.className = 'footer-link-group mb-3';
        newGroup.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="footer_links[${linkCount}][text]" class="form-control mb-2" placeholder="Link Text">
                </div>
                <div class="col-md-5">
                    <input type="text" name="footer_links[${linkCount}][url]" class="form-control mb-2" placeholder="Link URL">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-link">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(newGroup);
        linkCount++;

        // Show all remove buttons when there's more than one link
        const removeButtons = document.querySelectorAll('.remove-link');
        removeButtons.forEach(button => button.style.display = 'block');
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-link')) {
            const group = e.target.closest('.footer-link-group');
            group.remove();

            // Hide the remove button if only one link remains
            const removeButtons = document.querySelectorAll('.remove-link');
            if (removeButtons.length === 1) {
                removeButtons[0].style.display = 'none';
            }
        }
    });
});
</script>
                <div class="text-center mt-4">

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input handling
        document.getElementById('logo').addEventListener('change', function (e) {
            const fileName = e.target.files[0]?.name || 'Choose Logo File';
            this.previousElementSibling.querySelector('span').textContent = fileName;
        });

        // Route checking with debounce
        let checkTimeout;
        function checkRoute() {
            clearTimeout(checkTimeout);
            const route = document.getElementById('route').value;
            const statusElement = document.getElementById('route_status');

            if (route) {
                checkTimeout = setTimeout(() => {
                    fetch(`check_route.php?route=${encodeURIComponent(route)}`)
                        .then(response => response.text())
                        .then(data => {
                            if (data.includes("unavailable")) {
                                statusElement.innerHTML = `<i class="fas fa-times-circle text-danger"></i> Route unavailable`;
                                statusElement.style.color = 'var(--danger-color)';
                            } else {
                                statusElement.innerHTML = `<i class="fas fa-check-circle text-success"></i> Route available`;
                                statusElement.style.color = 'var(--success-color)';
                            }
                        });
                }, 500);
            } else {
                statusElement.innerHTML = '';
            }
        }

        function generateRandomRoute() {
            const randomRoute = Math.random().toString(36).substr(2, 5);
            document.getElementById('route').value = randomRoute;
            checkRoute();
        }
    </script>

    
<style>
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

/* Text size classes */
.text-large {
    font-size: 1.5em;
}

.text-normal {
    font-size: 1em;
}

.text-small {
    font-size: 0.875em;
}

/* Line spacing classes */
.spacing-1 {
    line-height: 1.5;
}

.spacing-2 {
    line-height: 2;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('blog-editor');
    const textarea = document.getElementById('blog');
    const formatBtns = document.querySelectorAll('.format-btn');
    const form = document.querySelector('form');

    // Initialize editor with textarea content if exists
    if (textarea.value) {
        editor.innerHTML = textarea.value;
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
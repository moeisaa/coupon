<?php
include('db_connect.php'); // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Retrieve existing page details
    $query = "SELECT * FROM pages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $stmt->close();
    
    if (!$page) {
        echo "<div class='alert alert-danger text-center' role='alert'>Page not found.</div>";
        exit;
    }

// Get form values
$route = isset($_POST['route']) ? $conn->real_escape_string($_POST['route']) : $page['route'];
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : $page['rating'];
$votes = isset($_POST['votes']) ? intval($_POST['votes']) : $page['votes']; // Add this line
$header = isset($_POST['header']) ? $conn->real_escape_string($_POST['header']) : $page['header'];
$description = $conn->real_escape_string(str_replace(array("\r", "\n"), '', $_POST['description']));
// Clean and preserve HTML formatting for blog content
$allowedTags = [
    'div', 'span', 'p', 'br', 'strong', 'em', 'u', 'strike', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
    'ul', 'ol', 'li', 'blockquote'
];
$allowedTagsStr = '<' . implode('><', $allowedTags) . '>';
$blog = isset($_POST['blog']) ? strip_tags($_POST['blog'], $allowedTagsStr) : $page['blog'];

// Preserve only our specific formatting classes
$validClasses = ['text-large', 'text-normal', 'text-small', 'spacing-1', 'spacing-2'];
$blog = preg_replace_callback(
    '/class="([^"]*)"/',
    function($matches) use ($validClasses) {
        $classes = array_filter(
            explode(' ', $matches[1]),
            function($class) use ($validClasses) {
                return in_array($class, $validClasses);
            }
        );
        return !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '';
    },
    $blog
);



// Get footer_id from form
$footer_id = isset($_POST['footer_id']) ? intval($_POST['footer_id']) : null;

// If no footer is selected, try to get the default one
if (!$footer_id) {
    $footer_result = $conn->query("SELECT id FROM footers WHERE is_default = 1 LIMIT 1");
    if ($footer = $footer_result->fetch_assoc()) {
        $footer_id = $footer['id'];
    }
}



$store_name = isset($_POST['store_name']) ? $conn->real_escape_string($_POST['store_name']) : $page['store_name'];
$default_coupon_url = isset($_POST['default_coupon_url']) ? $conn->real_escape_string($_POST['default_coupon_url']) : $page['default_coupon_url'];
$text_direction = isset($_POST['text_direction']) ? $conn->real_escape_string($_POST['text_direction']) : $page['text_direction'];
$theme = isset($_POST['theme']) ? $conn->real_escape_string($_POST['theme']) : 'theme1';
$language_id = isset($_POST['language_id']) ? intval($_POST['language_id']) : null;


    // Handle file upload
    $logo = $page['logo'];
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'static_pages/';
        $logo = basename($_FILES['logo']['name']);
        $target_file = $target_dir . $logo;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            echo "<div class='alert alert-danger text-center' role='alert'>Sorry, there was an error uploading your file.</div>";
            exit;
        }
    }

    // Check if the new route already exists (excluding current ID)
    if ($route !== $page['route']) {
        $checkRouteSql = "SELECT COUNT(*) FROM pages WHERE route = ? AND id != ?";
        $checkStmt = $conn->prepare($checkRouteSql);
        $checkStmt->bind_param('si', $route, $id);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            echo "<div class='alert alert-danger text-center' role='alert'>Route already exists!</div>";
            exit;
        }
    }
// Add this with other form field processing
$theme = isset($_POST['theme']) ? $conn->real_escape_string($_POST['theme']) : 'theme1';
$theme_color = isset($_POST['theme_color']) ? $conn->real_escape_string($_POST['theme_color']) : 'blue';
$custom_color = isset($_POST['custom_color']) ? $conn->real_escape_string($_POST['custom_color']) : null;

$sql = "UPDATE pages SET 
    route = ?, 
    rating = ?, 
    votes = ?,
    header = ?, 
    description = ?, 
    blog = ?, 
    logo = ?, 
    store_name = ?, 
    default_coupon_url = ?, 
    text_direction = ?,
    theme = ?,
    theme_color = ?,
    custom_color = ?,
    language_id = ?,
    footer_id = ?
WHERE id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}


if(!$stmt->bind_param('siissssssssssiii', 
    $route, $rating, $votes, $header, $description, $blog, 
    $logo, $store_name, $default_coupon_url, $text_direction,
    $theme, $theme_color, $custom_color, $language_id, 
    $footer_id, $id)) {
    die("Error binding parameters: " . $stmt->error);
}

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
  
if ($stmt->execute()) {
        // Generate updated static HTML page
        $html_content = "
        <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>$header</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; direction: $text_direction; }
                    .coupon { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
                    img { max-width: 200px; }
                </style>
            </head>
            <body>
                <img src='static_pages/$logo' alt='Logo'>
                <h1>$header</h1>
                <p>$description</p>
                <div>$blog</div>
            </body>
        </html>";

        // Write HTML content to a file
        file_put_contents("static_pages/$route.html", $html_content);
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Update Success</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <style>
                :root {
                    --success-color: #2ecc71;
                    --text-color: #2c3e50;
                    --bg-color: #f5f7fb;
                }
        
                body {
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                    background-color: var(--bg-color);
                    margin: 0;
                    padding: 1rem;
                }
        
                .success-container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
                    padding: 2.5rem;
                    width: 100%;
                    max-width: 500px;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }
        
                .success-icon {
                    font-size: 4rem;
                    color: var(--success-color);
                    margin-bottom: 1.5rem;
                    animation: scaleIn 0.5s ease-out;
                }
        
                .success-title {
                    font-size: 1.8rem;
                    font-weight: 600;
                    color: var(--text-color);
                    margin-bottom: 1rem;
                    opacity: 0;
                    animation: fadeInUp 0.5s ease-out forwards;
                    animation-delay: 0.2s;
                }
        
                .success-message {
                    color: var(--text-color);
                    opacity: 0.8;
                    margin-bottom: 1.5rem;
                    opacity: 0;
                    animation: fadeInUp 0.5s ease-out forwards;
                    animation-delay: 0.4s;
                }
        
                .redirect-text {
                    color: var(--text-color);
                    opacity: 0.7;
                    margin-bottom: 0;
                    opacity: 0;
                    animation: fadeInUp 0.5s ease-out forwards;
                    animation-delay: 0.6s;
                }
        
                .home-link {
                    color: var(--success-color);
                    text-decoration: none;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
        
                .home-link:hover {
                    opacity: 0.8;
                }
        
                .progress-bar {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 4px;
                    background: var(--success-color);
                    width: 100%;
                    transform-origin: left;
                    animation: progress 2s linear;
                }
        
                @keyframes scaleIn {
                    0% { transform: scale(0); }
                    60% { transform: scale(1.2); }
                    100% { transform: scale(1); }
                }
        
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
        
                @keyframes progress {
                    from { transform: scaleX(1); }
                    to { transform: scaleX(0); }
                }
            </style>
        </head>
        <body>
            <div class="success-container">
                <i class="fas fa-check-circle success-icon"></i>
                <h1 class="success-title">Success!</h1>
                <p class="success-message">Page has been updated successfully!</p>
                <p class="redirect-text">
                    Redirecting to <a href="admin.php" class="home-link">Home</a> in a few seconds...
                </p>
                <div class="progress-bar"></div>
            </div>
        
            <script>
                setTimeout(function() {
                    document.body.style.opacity = "0";
                    document.body.style.transition = "opacity 0.5s ease";
                    setTimeout(function() {
                        window.location.href = "admin.php";
                    }, 500);
                }, 1500);
            </script>
        </body>
        </html>';
    } else {
        echo "<div class='alert alert-danger text-center' role='alert'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<div class='alert alert-danger text-center' role='alert'>Invalid request.</div>";
}
?>

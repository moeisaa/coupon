<?php
include('db_connect.php'); // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route = $_POST['route'] ? $_POST['route'] : uniqid();
    $rating = intval($_POST['rating']);
    $votes = intval($_POST['votes']); // Get votes from form

    $header = $conn->real_escape_string($_POST['header']);
    $description = $conn->real_escape_string(str_replace(array("\r", "\n"), '', $_POST['description']));
    $store_name = $conn->real_escape_string($_POST['store_name']);
    $default_coupon_url = $conn->real_escape_string($_POST['default_coupon_url']);
    $text_direction = $conn->real_escape_string($_POST['text_direction']);
    $language_id = isset($_POST['language_id']) ? intval($_POST['language_id']) : null;
    
// Clean and preserve HTML formatting for blog content
$allowedTags = [
    'div', 'span', 'p', 'br', 'strong', 'em', 'u', 'strike', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
    'ul', 'ol', 'li', 'blockquote'
];
$allowedTagsStr = '<' . implode('><', $allowedTags) . '>';
$blog = strip_tags($_POST['blog'], $allowedTagsStr);

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


    // Handle file upload
    $logo = $_FILES['logo']['name'];
    $target_dir = "static_pages/";
    $target_file = $target_dir . basename($logo);
    $uploadOk = 1;

    if ($logo) {
        if ($_FILES['logo']['error'] != UPLOAD_ERR_OK) {
            echo "Error uploading file.";
            exit;
        }

        if ($_FILES['logo']['size'] > 5 * 1024 * 1024) {
            echo "File is too large.";
            $uploadOk = 0;
        }

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "File was not uploaded.";
            exit;
        } else {
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        }
    }

    $theme = $conn->real_escape_string($_POST['theme']);
    $theme_color = $conn->real_escape_string($_POST['theme_color'] ?? 'blue');
    $custom_color = $conn->real_escape_string($_POST['custom_color'] ?? null);
    
    $sql = "INSERT INTO pages (route, rating, votes, header, description, blog, store_name, 
    default_coupon_url, text_direction, theme, theme_color, custom_color, language_id"
    . ($logo ? ", logo" : "") . ") 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?"
    . ($logo ? ", ?" : "") . ")";
    
    $stmt = $conn->prepare($sql);

    $types = "siisssssssssi"; // Add 'i' for language_id
    if ($logo) {
        $types .= "s";
    }

    if ($logo) {
        $stmt->bind_param($types, $route, $rating, $votes, $header, $description, $blog, 
                          $store_name, $default_coupon_url, $text_direction, $theme, 
                          $theme_color, $custom_color, $language_id, $logo);
    } else {
        $stmt->bind_param($types, $route, $rating, $votes, $header, $description, $blog,
                          $store_name, $default_coupon_url, $text_direction, $theme,
                          $theme_color, $custom_color, $language_id);
    }
    
    if ($stmt->execute()) {
        $id = $conn->insert_id;

        $page = $conn->query("SELECT * FROM pages WHERE id=$id")->fetch_assoc();
        if (!$page) {
            echo "Error fetching page data: " . $conn->error;
            exit;
        }

        $header = htmlspecialchars($page['header']);
        $description = htmlspecialchars($page['description']);
        $blog = html_entity_decode($page['blog'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $logo = htmlspecialchars($page['logo']);
        $store_name = htmlspecialchars($page['store_name']);
        $default_coupon_url = htmlspecialchars($page['default_coupon_url']);
        $text_direction = htmlspecialchars($page['text_direction']);

        $coupons_sql = "SELECT * FROM coupons WHERE page_id = $id";
        $coupons_result = $conn->query($coupons_sql);
        $coupons_html = '';
        while ($coupon = $coupons_result->fetch_assoc()) {
            $coupon_title = htmlspecialchars($coupon['title']);
            $coupon_description = htmlspecialchars($coupon['description']);
            $coupon_details = htmlspecialchars($coupon['details']);
            $coupon_expire_date = htmlspecialchars($coupon['expire_date']);
            $coupon_photo = htmlspecialchars($coupon['photo']);
            $coupons_html .= "<div class='coupon'>
                                <h3>$coupon_title</h3>
                                <p><strong>Description:</strong> $coupon_description</p>
                                <p><strong>Details:</strong> $coupon_details</p>
                                <p><strong>Expire Date:</strong> $coupon_expire_date</p>
                                <img src='static_pages/$coupon_photo' alt='Coupon Photo'>
                              </div>";
        }

        $html_content = "
        <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>$header</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; direction: $text_direction; }
                    .lang-switcher { margin-bottom: 20px; }
                    .lang-switcher button { padding: 10px; margin: 0 5px; font-size: 16px; cursor: pointer; border: none; border-radius: 5px; }
                    .lang-switcher button:nth-child(1) { background-color: #007bff; color: white; }
                    .lang-switcher button:nth-child(2) { background-color: #28a745; color: white; }
                    .lang-switcher button:hover { opacity: 0.8; }
                    .coupon { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
                    .coupon img { max-width: 100px; height: auto; border-radius: 5px; }
                </style>
                <script>
                    function switchLanguage(lang) {
                        var header = document.getElementById('header');
                        var description = document.getElementById('description');
                        var blog = document.getElementById('blog');
                        var langEn = document.getElementById('lang_en');
                        var langAr = document.getElementById('lang_ar');

                        if (lang === 'en') {
                            header.innerText = '$header';
                            description.innerText = '$description';
                            blog.innerHTML = '$blog';
                            langEn.style.display = 'none';
                            langAr.style.display = 'block';
                        } else {
                            header.innerText = '$header';
                            description.innerText = '$description';
                            blog.innerHTML = '$blog';
                            langEn.style.display = 'block';
                            langAr.style.display = 'none';
                        }
                    }
                </script>
            </head>
            <body onload='switchLanguage(\"en\")'>
                <div class='lang-switcher'>
                    <button onclick='switchLanguage(\"en\")'>English</button>
                    <button onclick='switchLanguage(\"ar\")'>Arabic</button>
                </div>
                <img src='static_pages/$logo' alt='Logo'>
                <h1 id='header'>$header</h1>
                <p id='description'>$description</p>
                <div id='blog'>$blog</div>
                $coupons_html
            </body>
        </html>";

        file_put_contents("static_pages/$route", $html_content);
        echo '<style>
    :root {
        --success-color: #2ecc71;
        --text-color: #2c3e50;
        --bg-color: #f5f7fb;
    }

    .success-page {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--bg-color);
    }

    .success-container {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        max-width: 90%;
        width: 400px;
        position: relative;
        overflow: hidden;
        animation: slideIn 0.5s ease-out;
    }

    .success-icon {
        font-size: 4rem;
        color: var(--success-color);
        margin-bottom: 1.5rem;
        animation: scaleIn 0.5s ease-out;
    }

    .success-title {
        font-size: 1.5rem;
        color: var(--text-color);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .redirect-text {
        color: var(--text-color);
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
        animation: progress 3s linear;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        0% { transform: scale(0); }
        60% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes progress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }
</style>

<div class="success-page">
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1 class="success-title">Page Created Successfully!</h1>
        <p class="redirect-text">Redirecting to your page in a few seconds...</p>
        <div class="progress-bar"></div>
    </div>
</div>

<script>
    setTimeout(function() {
        document.body.style.opacity = "0";
        document.body.style.transition = "opacity 0.5s ease";
        setTimeout(function() {
            window.location.href = \'' . $route . '\';
        }, 500);
    }, 2500);
</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
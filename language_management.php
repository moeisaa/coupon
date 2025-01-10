<?php
require_once 'db_connect.php';

// Add new language
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_language'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $code = $conn->real_escape_string($_POST['code']);
    $direction = $conn->real_escape_string($_POST['direction']);
       // Check if language code already exists
    $check = $conn->query("SELECT code FROM languages WHERE code = '$code'");
    if ($check->num_rows > 0) {
        $error = "Language code already exists!";
    } else {
        $sql = "INSERT INTO languages (name, code, direction) VALUES ('$name', '$code', '$direction')";
        if($conn->query($sql)) {
            header('Location: language_management.php');
            $success = "Language added successfully!";
            exit();
        } else {
            $error = "Error adding language!";
        }
    }

}

// Delete language
if (isset($_GET['delete'])) {
   $id = (int)$_GET['delete'];
   $conn->query("DELETE FROM languages WHERE id = $id");
   header('Location: language_management.php');
   exit();
}

// Fetch languages
$result = $conn->query("SELECT * FROM languages");

// Add default languages
if (isset($_POST['add_default_languages'])) {
   $check = $conn->query("SELECT code FROM languages WHERE code IN ('en', 'ar')");
   if ($check->num_rows > 0) {
       $error = "Default languages already exist!";
   } else {
       // Insert English
       $conn->query("INSERT INTO languages (name, code, direction) VALUES ('English', 'en', 'ltr')");
       $en_id = $conn->insert_id;
       
       // Insert Arabic 
       $conn->query("INSERT INTO languages (name, code, direction) VALUES ('Arabic', 'ar', 'rtl')");
       $ar_id = $conn->insert_id;

       // English translations array
       $en_translations = [
           'all' => 'All', 'deals' => 'Deals', 'codes' => 'Codes',
           'verified' => 'verified', 'vote' => 'Voted', 
           'customers_voted' => 'customers voted with average', 'stars' => 'stars',
           'show coupon' => 'Show Coupon', 'details' => 'Details', 'deal' => 'deal',
           'code' => 'code', 'expiry' => 'Expiry', 'rating' => 'Rating',
           'about' => 'About Us', 'privacy' => 'Privacy Policy', 
           'contact' => 'Contact Us', 'copyright' => '© 2024 Copy right | all rights reserved',
           'go to store' => 'GO TO STORE', 'did it work?' => 'Did it work?',
           'share' => 'Share', 'terms' => 'Terms and conditions',
           'copycode' => 'COPY CODE', 'get deal' => 'Get Deal',
           'copy msg' => 'Copy and paste this code in',
           'deal msg' => 'Your offer has been activated. Go to the store',
           'available deals and coupons' => 'Coupons & Offers available today.',
           'users today' => 'users today', 'best coupons' => 'Best Coupons In World',
           'share coupon' => 'Share Coupon', 'copy link' => 'Copy Link',
           'link copied' => 'Link copied successfully!',
           'deal msg start' => 'Please Press Button to Activate your deal in'
       ];

       // Arabic translations array  
       $ar_translations = [
           'all' => 'الكل', 'deals' => 'العروض', 'codes' => 'الكوبونات',
           'verified' => 'محقق', 'vote' => 'صوّت',
           'customers_voted' => 'عميلًا بمتوسط', 'stars' => 'نجوم', 
           'show coupon' => 'عرض الكوبون', 'details' => 'التفاصيل',
           'deal' => 'عرض', 'code' => 'كوبون', 'expiry' => 'ينتهي في',
           'rating' => 'التقييمات', 'about' => 'من نحن',
           'privacy' => 'سياسة الخصوصية', 'contact' => 'تواصل معنا',
           'copyright' => 'جميع الحقوق محفوظة',
           'go to store' => 'الذهاب إلى المتجر',
           'did it work?' => 'هل احببت الكوبون؟', 'share' => 'مشاركة',
           'terms' => 'الشروط والأحكام', 'copycode' => 'نسخ الكود',
           'get deal' => 'الحصول علي الصفقة',
           'copy msg' => 'انسخ والصق هذا الكود في',
           'deal msg' => 'لقد تم تفعيل العرض الخاص بك قم بالذهاب للمتجر',
           'available deals and coupons' => 'كوبونات وعروض متاحه لهذا اليوم',
           'users today' => 'مستخدم',
           'deal msg start' => 'قم بالضغط لتفعيل العرض الخاص بك في',
           'share coupon' => 'مشاركة الكوبون', 'copy link' => 'نسخ الرابط',
           'link copied' => 'تم نسخ الرابط بنجاح!',
           'best coupons' => 'افضـل الكوبونات في الوطن العربي'
       ];

       // Insert translations
       foreach($en_translations as $key => $value) {
           $conn->query("INSERT INTO translations (language_id, translation_key, translation_value) 
                        VALUES ($en_id, '" . $conn->real_escape_string($key) . "', '" . $conn->real_escape_string($value) . "')");
       }

       foreach($ar_translations as $key => $value) {
           $conn->query("INSERT INTO translations (language_id, translation_key, translation_value) 
                        VALUES ($ar_id, '" . $conn->real_escape_string($key) . "', '" . $conn->real_escape_string($value) . "')");
       }

       $success = "Default languages and translations added successfully!";
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --content-margin: 300px;
        }

        @media (max-width: 991px) {
            :root {
                --content-margin: 0;
            }
        }

        .main-content {
            margin-left: var(--content-margin);
            padding: 20px;
            transition: margin-left 0.3s;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
            }
        }

        .card {
            overflow: hidden;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1 1 200px;
        }

        @media (max-width: 768px) {
            .form-group {
                flex: 1 1 100%;
            }

            .btn-group {
                width: 100%;
                display: flex;
                justify-content: space-between;
            }

            .table-responsive {
                margin: 0 -15px;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
        }

        .table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        @media (max-width: 576px) {
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>

    <div class="main-content">
        <h2 class="mb-4">Language Management</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">Add New Language</div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Language Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="code" class="form-control" placeholder="Language Code (e.g. en)" required>
                        </div>
                        <div class="form-group">
                            <select name="direction" class="form-control" required>
                                <option value="ltr">Left to Right</option>
                                <option value="rtl">Right to Left</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="add_language" class="btn btn-primary w-100">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" class="mb-4">
            <button type="submit" name="add_default_languages" class="btn btn-success w-100">
                Add Default Languages (EN/AR)
            </button>
        </form>

        <div class="card">
            <div class="card-header">Languages</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Direction</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['code']); ?></td>
                                <td><?php echo htmlspecialchars($row['direction']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_translations.php?lang_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info mb-1 mb-md-0">Edit Translations</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
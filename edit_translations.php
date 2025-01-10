<?php
require_once 'db_connect.php';

$lang_id = (int)$_GET['lang_id'];
$lang_result = $conn->query("SELECT * FROM languages WHERE id = $lang_id");
$language = $lang_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   foreach ($_POST['translations'] as $key => $value) {
       $value = $conn->real_escape_string($value);
       $key = $conn->real_escape_string($key);
       
       $check = $conn->query("SELECT id FROM translations WHERE language_id = $lang_id AND translation_key = '$key'");
       
       if ($check->num_rows > 0) {
           $conn->query("UPDATE translations SET translation_value = '$value' WHERE language_id = $lang_id AND translation_key = '$key'");
       } else {
           $conn->query("INSERT INTO translations (language_id, translation_key, translation_value) VALUES ($lang_id, '$key', '$value')");
       }
   }
   header('Location: language_management.php');
   exit();
}

$trans_result = $conn->query("SELECT translation_key, translation_value FROM translations WHERE language_id = $lang_id");
$translations = [];
while ($row = $trans_result->fetch_assoc()) {
   $translations[$row['translation_key']] = $row['translation_value'];
}

$default_keys = [
   'all' => 'All', 'deals' => 'Deals', 'codes' => 'Codes',
   'verified' => 'Verified', 'vote' => 'Voted',
   'customers_voted' => 'customers voted with average', 'stars' => 'stars', 
   'show coupon' => 'Show Coupon', 'details' => 'Details',
   'deal' => 'Deal', 'code' => 'Code', 'expiry' => 'Expiry',
   'rating' => 'Rating', 'about' => 'About Us',
   'privacy' => 'Privacy Policy', 'contact' => 'Contact Us',
   'copyright' => '© 2024 Copy right | all rights reserved',
   'go to store' => 'GO TO STORE', 'did it work?' => 'Did it work?',
   'share' => 'Share', 'terms' => 'Terms and conditions',
   'copycode' => 'COPY CODE', 'get deal' => 'Get Deal',
   'copy msg' => 'Copy and paste this code in',
   'deal msg' => 'Your offer has been activated. Go to the store',
   'available deals and coupons' => 'Coupons & Offers available today',
   'users today' => 'users today',
   'best coupons' => 'Best Coupons In World',
   'share coupon' => 'Share Coupon', 'copy link' => 'Copy Link',
   'link copied' => 'Link copied successfully!',
   'deal msg start' => 'Please Press Button to Activate your deal in'
];

include('sidebar.php');
?>

<style>
.translation-container {
    padding: 15px;
    transition: all 0.3s ease;
}

@media (min-width: 769px) {
    .translation-container {
        margin-left: 280px; /* Sidebar width */
    }
}

@media (max-width: 768px) {
    .translation-container {
        margin-left: 0;
        padding: 10px;
    }
    
    .table-block tr {
        display: block;
        margin-bottom: 1rem;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
    }
    
    .table-block td {
        display: block;
        border: none;
    }
    
    .table-block td:first-child {
        font-weight: bold;
        background: #f8f9fa;
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 8px;
    }
}
</style>

<div class="translation-container">
   <h2 class="mb-4">Edit Translations - <?php echo htmlspecialchars($language['name']); ?></h2>
   
   <div class="card">
       <div class="card-body">
           <form method="POST">
               <div class="table-responsive">
                   <table class="table">
                       <thead>
                           <tr>
                               <th>Key</th>
                               <th>Translation</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php foreach ($default_keys as $key => $default_value): ?>
                           <tr>
                               <td><?php echo htmlspecialchars($key); ?></td>
                               <td>
                                   <input type="text" 
                                       name="translations[<?php echo htmlspecialchars($key); ?>]" 
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($translations[$key] ?? ''); ?>"
                                       dir="<?php echo $language['direction']; ?>"
                                       placeholder="<?php echo htmlspecialchars($default_value); ?>"
                                       required>
                               </td>
                           </tr>
                           <?php endforeach; ?>
                       </tbody>
                   </table>
               </div>
               <button type="submit" class="btn btn-primary mt-3">Save Translations</button>
           </form>
       </div>
   </div>
</div>
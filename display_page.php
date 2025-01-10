<?php 

// Read and parse .env file
$env = parse_ini_file(__DIR__ . '/.env');

// Database connection details
$servername = $env['DB_HOST'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Add these helper functions at the top of the file
function hexToRgba($hex, $alpha = 1) {
    // Remove the '#' character from the beginning of the hex color code
    $hex = str_replace('#', '', $hex);

    // Convert the hex color code to RGB values
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Return the RGBA value as a string
    return "rgba({$r}, {$g}, {$b}, {$alpha})";
}
function adjustColorBrightness($hex, $steps) {
    $hex = ltrim($hex, '#');
    $rgb = array_map('hexdec', str_split($hex, 2));
    foreach ($rgb as &$color) {
        $color = max(0, min(255, $color + $steps));
    }
    return '#' . implode('', array_map(function($n) {
        return str_pad(dechex($n), 2, '0', STR_PAD_LEFT);
    }, $rgb));
}

// Check for route
if (isset($_GET['route'])) {
    $route = $conn->real_escape_string($_GET['route']);
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    // add img icon according to filter type 
 $img_for_all = $filter == 'all'?'active-icon1.svg':'icon-1.svg' ;
 $img_for_code = $filter == 'code'?'active-icon2.svg':'icon-2.svg' ;
 $img_for_deal = $filter == 'deal'?'active-icon3.svg':'icon-3.svg' ;
 $all_filter = 'all';
 $deal_filter = 'deal';
 $code_filter = 'code';
    // Fetch page details
    $page_query = $conn->query("SELECT * FROM pages WHERE route='$route'");
    if (!$page_query) {
        die("Query failed: " . $conn->error);
    }

    $page = $page_query->fetch_assoc();
    if (!$page) {
        // Use JavaScript to redirect if the page is not found
        echo  "<script>window.location.href = '".$env['REDIRECT']."';</script>";
        exit;
    }

    // Sanitize output
    $header = htmlspecialchars($page['header']);
    $description = htmlspecialchars($page['description']);
    $blog = html_entity_decode($page['blog'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $logo = htmlspecialchars($page['logo']);
    $rating = htmlspecialchars($page['rating']);
    $store_name = htmlspecialchars($page['store_name']);
    $text_direction = htmlspecialchars($page['text_direction']);
    $default_coupon_url = $page['default_coupon_url'] ;
    $votes = htmlspecialchars($page['votes']);

// Get language details from page
$language_id = $page['language_id']; 
$trans_result = $conn->query("SELECT translation_key, translation_value FROM translations WHERE language_id = $language_id");

$translations = [];
while ($row = $trans_result->fetch_assoc()) {
    $translations[$row['translation_key']] = $row['translation_value'];
}

$direction = $page['text_direction'];

    // Fetch coupons and count them based on type
    $coupon_filter_query = "";
    if ($filter == 'code') {
        $coupon_filter_query = " AND type='code'";
    } elseif ($filter == 'deal') {
        $coupon_filter_query = " AND type='deal'";
    }

  // Modify the coupons query in display_page.php
$coupons_sql = "SELECT *, COALESCE(daily_uses, fake_initial_uses) as display_uses 
FROM coupons 
WHERE page_id = {$page['id']} $coupon_filter_query 
ORDER BY position";

$coupons_result = $conn->query($coupons_sql);
if (!$coupons_result) {
die("Coupons query failed: " . $conn->error);
}
    // Count all coupons, deals, and codes
    $total_coupons_query = "SELECT COUNT(*) as total, 
                            SUM(CASE WHEN type='code' THEN 1 ELSE 0 END) as total_codes, 
                            SUM(CASE WHEN type='deal' THEN 1 ELSE 0 END) as total_deals 
                            FROM coupons WHERE page_id = {$page['id']}";
    $counts_result = $conn->query($total_coupons_query);
    $counts = $counts_result->fetch_assoc();

    $total_coupons = $counts['total'];
    $total_codes = $counts['total_codes'];
    $total_deals = $counts['total_deals'];

// Count total uses across all coupons
$total_uses_query = "SELECT SUM(COALESCE(daily_uses, fake_initial_uses)) as total_uses 
                     FROM coupons 
                     WHERE page_id = {$page['id']}";
$total_uses_result = $conn->query($total_uses_query);
$total_uses = $total_uses_result->fetch_assoc()['total_uses'];


    // Assuming $coupon_photo is set somewhere in your code

// Fetch site logo from settings
$site_logo_query = "SELECT setting_value FROM settings WHERE setting_key = 'site_logo' LIMIT 1";
$site_logo_result = $conn->query($site_logo_query);
$site_logo_path = 'static_pages/images/logo (1).svg'; // Default value

if ($site_logo_result && $site_logo_result->num_rows > 0) {
    $site_logo_path = $site_logo_result->fetch_assoc()['setting_value'];
}


// Determine if the 'hovered-img' class should be added


    // Generate coupon HTML
    $coupons_html = '';
    while ($coupon = $coupons_result->fetch_assoc()) {
        $coupon_title = htmlspecialchars($coupon['title']);
        $coupon_description = htmlspecialchars($coupon['description']);
        $coupon_details = htmlspecialchars($coupon['details']);
        $coupon_expire_date = htmlspecialchars($coupon['expire_date']);
        $coupon_photo = htmlspecialchars($coupon['photo']);
        $coupon_type = htmlspecialchars($coupon['type']);
        $coupon_code = htmlspecialchars($coupon['code']);
        $coupon_url = htmlspecialchars($coupon['url']);
        $coupon_idd = htmlspecialchars($coupon['id']);
        $coupon_message = '';
        if ($coupon_type === 'deal') {
          // $coupon_message = "<h5>" . htmlspecialchars($translations['no code needed']) . "</h5>";
           $deal_msg = "<h3>" . htmlspecialchars($translations['deal msg']) . "</h3>" ;
           $code_msg = '';
           $store_link = '  <div class = "store text-center">
                                             <a href='.$coupon_url.' target="_blank" class = "$bg_red">' . htmlspecialchars($translations["go to store"]) . '</a>
                                    

                                          </div>';
            $btn_txt = htmlspecialchars($translations['get deal']);
            $bg_red = 'bg-red';
        } else {
            $coupon_message = '
                <div class="code-value">
                    <h6 id="code_' . htmlspecialchars($coupon_idd) . '">' . htmlspecialchars($coupon_code) . '</h6> 
                </div>
                <div class="copy text-center" style="cursor: pointer;" onclick="copyCode(\'' . htmlspecialchars($coupon_url) . '\', \'' . htmlspecialchars($coupon_idd) . '\')">
                    <h6>
                        <img src="static_pages/images/copy.svg">  ' . htmlspecialchars($translations['copycode']) .'
                    </h6>
                </div>';
                $store_link = '';
                $deal_msg = '' ;
                $code_msg = "<h4>" . htmlspecialchars($translations['copy msg']) . "   <a href = '$coupon_url'>$store_name</a></4>" ;
                $btn_txt = htmlspecialchars($translations['show coupon']);
                $bg_red = '';
        }
        


        $coupon_url_with_modal = "$route#$coupon[id]";

        // check coupon type to translate it according its type 
        $translated_coupon_type = ($coupon_type === 'deal') ? htmlspecialchars($translations['deal']) : htmlspecialchars($translations['code']);

        // put image according to coupon type 
        $image_src = ($coupon_type === 'deal') ? 'static_pages/images/deal.svg' : 'static_pages/images/code.svg';

        // coupon photo when hover 
        $hovered_class = !empty($coupon_photo) ? 'hovered-img' : 'd-none';

        $coupon_img = !empty($coupon_photo) ? '' : 'd-none';

        // Display coupon information
        $coupons_html .= "<div class=''>
        
                    <div class='coupons-cards' data-type='{$coupon_type}'>
                      <div class='coupon-container d-flex '>
                        <div class='discount d-flex flex-column justify-content-between '>
                         <div class = 'd-flex justify-content-center align-items-center discount-txt flex-column h-100 active-state'>
                            <h4>$coupon_title</h4>

                               <div class = ' $hovered_class'>
                                        <img src='static_pages/$coupon_photo' class=''> 
                                       </div>
                          </div>
                          <div class='verify-status'>
                           
                              <h5> " . htmlspecialchars($translations['verified']) . " </h5>
                              <img src='static_pages/images/icon-5.svg' alt=''>
                           
                          </div>
                        
                                      
                        </div> 

                        <div class='coupon-card'>
                          <div class='coupon-body'>
                            <div class='code'>
                              <span> <img src = ' $image_src' class= ''> $translated_coupon_type</span>
                            </div>
                            <div class='coupon-content d-flex justify-content-between '>
                              <div class='text'>
                                <p>
                                <a href = '$coupon_url'  onclick='showCouponModal(\"$coupon_type\" , \"$coupon_idd\", \"$coupon_url\" , \"$coupon_url_with_modal\")'>
                                $coupon_description
                                </a>
                               </p>
                              </div>
                              <div class='action-btn'>
                                <!-- Button trigger modal -->
<button type='button' class=' show-btn ' onclick='showCouponModal(\"$coupon_type\" , \"$coupon_idd\", \"$coupon_url\" , \"$coupon_url_with_modal\")'>
                          $btn_txt <i class='fa-solid fa-angle-right '></i>
                                </button>
                              </div>
                            </div>
                           <div class='coupon-info d-flex justify-content-between align-items-center'>

                            <h6 class='colored-txt details-toggle' style='cursor: pointer;'> " . htmlspecialchars($translations['details']) . "</h6>
<h5 id='usage-count-".$coupon_idd."' class='d-flex align-items-center gap-1'>
    <i class='fas fa-users' style='color: #FF4639; font-size: 0.9em;'></i>
    " . htmlspecialchars($coupon['display_uses']) . " " . htmlspecialchars($translations['users today']) . "
</h5>
                             <h5>" . htmlspecialchars($translations['expiry']) . "  $coupon_expire_date</h5>
                            </div>
                          
                                 
                             <!-- Hidden container for coupon details -->
                              <div class='coupon-details' style='display: none;'>
                               <p> $coupon_details</p>
                              </div>

                          </div>
                        </div>
                      </div>
                    </div>
                </div>

                <!-- Modal for deal -->
<div class='modal fade' id='dealModal_{$coupon['id']}' tabindex='-1' aria-labelledby='dealModalLabel_{$coupon['id']}' aria-hidden='true'>
                  <div class='modal-dialog  modal-dialog-centered'>
                    <div class='modal-content'>
                                     <div class='modal-header'>
                                       
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div> 
                                      
                                      <div class='modal-body'>
                                      <div class = 'title-container'>
                                        <div class='title  d-flex align-items-center'>
                                       <div class = 'coupon-img d-flex align-items-center  $coupon_img'>
                                        <img src='static_pages/$logo' class='me-3'>
                                       </div>
                                      
                                       <h6 class =''>$coupon_description</h6>
                                           
  
                                       </div>
                                      </div>

                                       <div class = 'copy-msg  deal-msg'>
                                       $code_msg
                                       $deal_msg
                                      </div>
                              
                                     

                                       <div class='info d-flex flex-column align-items-center justify-content-center'>
                                          <div class = 'action-btn'>
                                            <a href='$coupon_url' class = 'btn show text-white ' target='_blank'>" . htmlspecialchars($translations['go to store']) . "</a>
                                          </div>
                                         
                                          $store_link
                                          <div class='review d-flex ms-3 justify-content-between align-items-center'>
                                          <h6>" . htmlspecialchars($translations['did it work?']) . " </h6>
                                          <div class='icons '>
                                          <i class='fa-solid fa-thumbs-up'></i>
                                          <i class='fa-solid fa-thumbs-down'></i>
                                          </div>
                                          </div>

                                          <div class='share text-center'>
                                          <h6>" . htmlspecialchars($translations['share']) . "</h6>
                                          <img src='static_pages/images/whatsapp.svg'>

                                          </div>
                                        
                                       </div>
                                      </div>
                               
                                    </div>
                  </div>
                </div>

                <!-- Modal for code -->
<div class='modal fade' id='codeModal_{$coupon['id']}' tabindex='-1' aria-labelledby='codeModalLabel_{$coupon['id']}' aria-hidden='true'>

                  <div class='modal-dialog  modal-dialog-centered'>
                    <div class='modal-content'>
                                     <div class='modal-header'>
                                       
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div> 
                                      
                                      <div class='modal-body'>
                                      <div class = 'title-container'>
                                       <div class='title  d-flex align-items-center '>
                                        <div class = 'coupon-img    d-flex  align-items-center '>
                                        <img src='static_pages/$logo' class=''>
                                       </div>
                                       <h6 class =''>$coupon_description</h6>
                      
   </div>
                                       </div>

                                       <div class='info'>
                                       <div class = 'd-flex  align-items-center justify-content-center flex-column'>
                                      <div class = 'copy-msg  deal-msg'>
                                       $code_msg
                                       $deal_msg
                                      </div>

                                      
                                      <div class='d-flex align-items-center justify-content-center'>
                            

    $coupon_message

</div>
                                         
                                         <!-- <div class = 'store text-center'>
                                             <a href='$coupon_url' target='blank_' class = '$bg_red'>" . htmlspecialchars($translations['go to store']) . "</a>
                                    
                                          </div>
                                         -->
                                         $store_link
                                          <div class='review d-flex ms-3 justify-content-between align-items-center '>
                                          <h6>" . htmlspecialchars($translations['did it work?']) . " </h6>
                                          <div class='icons ms-3'>
                                          <i class='fa-solid fa-thumbs-up'></i>
                                          <i class='fa-solid fa-thumbs-down'></i>
                                          </div>
                                          </div>
                                            </div>
                                          <div class = 'terms'>
                                          <h6>" . htmlspecialchars($translations['terms']) . "<i class='fa-solid fa-caret-down'></i></h6>
                                          </div>

                                          <div class='share text-center'>
                                          <h6>" . htmlspecialchars($translations['share']) . " </h6>
                                          <img src='static_pages/images/whatsapp.svg'>

                                          </div>
                                        
                                       </div>
                                    </div>
                               
                                    </div>
                  </div>
                </div>";
    }

    $theme = $page['theme']; // Get the selected theme

    if ($theme === 'theme1') {
    // Output the HTML
    echo "
    <!DOCTYPE html>
<html lang='en' dir='$text_direction'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$header</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'>
    <link rel='stylesheet' href='static_pages/css/style.css'>
</head>
<body>

<!-- Navbar Section -->
<nav class='navbar navbar-expand-lg desktop-nav'>
    <div class='container'>
        <a class='navbar-brand' href='#'><h1><img src = 'static_pages/images/logo (1).svg' class = 'img-fluid'></h1></a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent'>
            <i class='fas fa-bars'></i>
        </button>
         <div class='collapse navbar-collapse' id='navbarSupportedContent'>
             <ul class='navbar-nav me-auto mb-2 mb-lg-0'></ul>
         </div>
    </div>
</nav>

<nav class = 'mob-nav'>
<div class='container'>
<div class = 'd-flex justify-content-evenly align-items-center'>
  <div class = 'menu'>
  <img src= 'static_pages/images/menu-bar.svg'>
  </div>
 
    <div class = 'logo'>
  <img src= 'static_pages/images/logo (1).svg'>
  </div>
  
   <div class = 'search'>
  <img src= 'static_pages/images/search.svg'>
  </div>
</div>
</div>
</nav>

  <!-- start mob-content section --- -->
       <section class='mob-content d-md-none'>
        <div class='container'>
          <div class='content d-flex'>
            <div class='img'>
            <a href = '$default_coupon_url)'>
                          <img src='static_pages/$logo' alt='' class='me-1'>
            </a>
            </div>
           
            <div class='txt'>
              <p>
                $header
              </p>

              <h6>
                <span>
<svg width='6' height='6' viewBox='0 0 6 6' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path d='M0.178106 2.82371L2.8266 0.174044C2.93758 0.0630159 3.09056 0 3.24952 0H5.40011C5.73005 0 6 0.270068 6 0.600151V2.75169C6 2.91073 5.93701 3.06377 5.82303 3.1748L3.17454 5.82446C2.94058 6.05851 2.55966 6.05851 2.3257 5.82446L0.175092 3.67293C-0.0588636 3.43887 -0.0588636 3.06077 0.178106 2.82371ZM4.65026 1.80046C4.89921 1.80046 5.10017 1.5994 5.10017 1.35034C5.10017 1.10128 4.89921 0.900228 4.65026 0.900228C4.4013 0.900228 4.20034 1.10128 4.20034 1.35034C4.20034 1.5994 4.4013 1.80046 4.65026 1.80046Z' fill='#222222'/>
</svg>
               " . htmlspecialchars($total_coupons) . "  " . htmlspecialchars($translations['available deals and coupons']) . " 

                </span>
              </h6>

            </div>

          </div>
        </div>
          
       </section>

    <!-- start mob-filteration section -- -->
<section class='mob-filteration d-md-none'>
  <div class='container'>
  <ul class = 'd-flex justify-content-evenly'>
  <a href='#' data-filter='all'>
    <li class='active-li'>
     
      " . htmlspecialchars($translations['all']) . " 
      <span>(<span id='total-count'>" . htmlspecialchars($total_coupons) . "</span>)</span>
    </li>
  </a>
  <a href='#' data-filter='deal'>
    <li>
    
      " . htmlspecialchars($translations['deals']) . " 
      <span>(<span id='deals-count'>" . htmlspecialchars($total_deals) . "</span>)</span>
    </li>
  </a>
  <a href='#' data-filter='code'>
    <li>
   
      " . htmlspecialchars($translations['codes']) . " 
      <span>(<span id='codes-count'>" . htmlspecialchars($total_codes) . "</span>)</span>
    </li>
  </a>
</ul>
  </div>
</section>
<!-- Coupons Section -->
<section class='coupons'>
    <div class='container'>
      <div class='row justify-content-between'>
               <div class='col-md-3'>

          <div class='sidebar'>
            <div class='about-grab'>
              <div class='img '>
              <a href = '$default_coupon_url'>
              <img src='static_pages/$logo' alt=''>
              </a>
                
              </div>
              <div class='content'>
                <h2 style='font-size:20px;'>$header</h2>
                <p  style='font-size:11px;'>" . htmlspecialchars($total_coupons) . "  " . htmlspecialchars($translations['available deals and coupons']) . "</p>
              </div>
            </div>
           
            
           <div class='filteration'>
<ul>
  <a href='#' data-filter='all'>
    <li class='active-li '>
     <img src = 'static_pages/images/$img_for_all'>
      " . htmlspecialchars($translations['all']) . " 
      <span>(<span id='total-count'>" . htmlspecialchars($total_coupons) . "</span>)</span>
    </li>
  </a>
  <a href='#' data-filter='deal'>
    <li class = ''>
     <img src = 'static_pages/images/$img_for_code'>
      " . htmlspecialchars($translations['deals']) . " 
      <span>(<span id='deals-count'>" . htmlspecialchars($total_deals) . "</span>)</span>
    </li>
  </a>
  <a href='#' data-filter='code'>
    <li class = ''>
    <img src = 'static_pages/images/$img_for_deal'>
      " . htmlspecialchars($translations['codes']) . " 
      <span>(<span id='codes-count'>" . htmlspecialchars($total_codes) . "</span>)</span>
    </li>
  </a>
</ul>
</div>

                <!-- rating  -->
                            <div class='rating'>
                    <h3>" . htmlspecialchars($translations['rating']) . "</h3>
                    <div class='stars d-flex'>";
    
    // Loop through 5 stars and dynamically add the active class based on the rating
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            echo "<i class='fa-solid fa-star active-star'></i>";
        } else {
            echo "<i class='fa-solid fa-star'></i>";
        }
    }

    echo "
                    </div>
<p>  " . htmlspecialchars($translations['vote']) . " " . htmlspecialchars($page['votes']) . " " . htmlspecialchars($translations['customers_voted']) . " " . $rating . " " . htmlspecialchars($translations['stars']) . "</p>                   
                </div>
            </div>
        </div>
           <div class='col-md-9'>
          $coupons_html
          </div>
          </div>
        </div>
     
</section>
<!-- start blog section -->
<section class='blogs'>
  <div class='container'>
    <div class='row justify-content-center'>
      <div class='col-md-3'>
      </div>
      <div class='col-md-9'>
        <div class='blog-container'>
          <div class='blog-content'>
            <h3>$store_name</h3>
            <div class='formatted-blog-content'>
               $blog
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <style>
  /* Text formatting classes */
.text-large { 
    font-size: 1.5em !important; 
}
.text-normal { 
    font-size: 1em !important; 
}
.text-small { 
    font-size: 0.875em !important; 
}
.spacing-1 { 
    line-height: 1.5 !important; 
}
.spacing-2 { 
    line-height: 2 !important; 
}

/* Blog content styles */
.formatted-blog-content {
    font-family: sans-serif;
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.formatted-blog-content > * { 
    margin-bottom: 1rem; 
}
.formatted-blog-content > *:last-child { 
    margin-bottom: 0; 
}
.formatted-blog-content p { 
    margin-bottom: 1rem; 
}
.formatted-blog-content strong { 
    font-weight: 600; 
}
.formatted-blog-content em { 
    font-style: italic; 
}

/* Blog container styles */
.blog-container {
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.blog-content h3 {
    color: #333;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}
  </style>
</section>

<!--start footer section -->
  <footer>
          <div class='footer-content'>
            <div class='footer-img'>
              <img src='static_pages/images/logo-2.svg' alt=''>
            </div>
            
            <div class='footer-links'>
              <ul class='d-flex justify-content-evenly'>
               <li>
                <a href='#'>" . htmlspecialchars($translations['contact']) . "</a>
               </li>

               <li>
                <a href='#'>" . htmlspecialchars($translations['privacy']) . "</a>
               </li>

               <li>
                <a href='#'>" . htmlspecialchars($translations['about']) . "</a>
               </li>
              </ul>
            </div>

            <p>" . htmlspecialchars($translations['copyright']) . "</p>
          </div>

          <div class='footer-bottom d-flex  justify-content-between'>
            <div class='left-shape '>
              
            </div>


            <div class='right-shape '>
            
            </div>
          </div>
        </footer>

<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>

<!--for modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the current hash value from the URL (without the # symbol)
    const hash = window.location.hash.substring(1); // Get '44'

    if (hash) {
        // Assuming the hash corresponds to the coupon ID (44)
        const modalElement = document.getElementById('codeModal_' + hash);

        if (modalElement) {
            // Initialize and show the modal
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }
});

</script>

<script>
function showCouponModal(type, coupon_id, coupon_url, new_tab_url) {
    // Track usage
    fetch('track_usage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'coupon_id=' + coupon_id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the display count for this coupon
            const usageElement = document.querySelector('#usage-count-' + coupon_id);
            if (usageElement) {
                usageElement.textContent = data.count + ' مستخدم اليوم';
            }
        }
    })
    .catch(error => console.error('Error:', error));

    // Original functionality
    window.open(new_tab_url, '_blank');
    setTimeout(function() {
        window.location.href = coupon_url;
    }, 50);
}
</script>

 <script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const modalId = urlParams.get('modal');
    if (modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }
});

    </script>

<!-- for active class on filteration -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all filter list items
        const filterItems = document.querySelectorAll('.filteration ul a , .mob-filteration ul a');

        // Function to update the active class and images based on data-filter
        function updateActiveClass() {
            // Loop through all filter items
            filterItems.forEach(item => {
                const liElement = item.querySelector('li');
                const imgElement = liElement.querySelector('img'); // Get the image inside li
                const filter = item.getAttribute('data-filter'); // Get filter from data-filter attribute

                console.log('Filter value:', filter); // Debugging

                if (liElement) {
                    if (liElement.classList.contains('active-li')) {
                        if (imgElement) updateImage(filter, imgElement, true); // Only update if image exists
                    } else {
                        if (imgElement) updateImage(filter, imgElement, false); // Only update if image exists
                    }
                }
            });
        }

        // Function to update the image based on active state
        function updateImage(filter, imgElement, isActive) {
            let imgSrc;

            console.log('Updating image for filter:', filter, 'isActive:', isActive); // Debugging

            if (filter === 'all') {
                imgSrc = isActive ? 'static_pages/images/active-icon1.svg' : 'static_pages/images/icon-1.svg';
            } else if (filter === 'deal') {
                imgSrc = isActive ? 'static_pages/images/active-icon2.svg' : 'static_pages/images/icon-2.svg';
            } else if (filter === 'code') {
                imgSrc = isActive ? 'static_pages/images/active-icon3.svg' : 'static_pages/images/icon-3.svg';
            } else {
                console.error('Unknown filter:', filter); // Handle unexpected filter values
            }

            // Ensure imgElement is not null and imgSrc is defined
            if (imgElement && imgSrc) {
                imgElement.src = imgSrc;
                console.log('Image source set to:', imgSrc); // Debugging
            } else {
                console.error('Image element is null or imgSrc is undefined'); // Debugging
            }
        }

        // Set the active class and images based on the initial state
        updateActiveClass();

        // Add click event listener to each filter item to handle manual filter selection
        filterItems.forEach(item => {
            item.addEventListener('click', function(event) {
                // Prevent default link behavior to avoid page refresh
                event.preventDefault();

                // Remove active class from all items
                filterItems.forEach(link => {
                    const liElement = link.querySelector('li');
                    const imgElement = liElement.querySelector('img');
                    const filter = link.getAttribute('data-filter');
                    if (liElement) liElement.classList.remove('active-li');
                    if (imgElement) updateImage(filter, imgElement, false);
                });

                // Add active class to the clicked item and update its image
                const liElement = this.querySelector('li');
                const imgElement = liElement.querySelector('img');
                const filter = this.getAttribute('data-filter');
                if (liElement) liElement.classList.add('active-li');
                if (imgElement) updateImage(filter, imgElement, true);
            });
        });
    });
</script>





<!-- Add the JavaScript for copying the code -->
<script>
function copyCode(couponUrl, couponId) {
    console.log('Coupon ID:', couponId); // Debugging: Check the coupon ID being used

    // Get the code element by its unique ID
    var codeElement = document.getElementById('code_' + couponId);

    if (codeElement) {
        var code = codeElement.innerText.trim(); // Ensure no extra whitespace
        console.log('Code:', code); // Debugging: Check the code being copied

        // Use the Clipboard API to copy the text
        navigator.clipboard.writeText(code).then(function() {
            // Show a success message with toast
            showToast('Code copied: ' + code);

            // Open the coupon URL in a new tab
            window.open(couponUrl, '_blank');
        }).catch(function(err) {
            // Handle any errors (optional)
            console.error('Failed to copy text: ', err);
        });
    } else {
        console.error('Code element not found for ID: ' + couponId);
    }
}

function showToast(message) {
    // Create the toast element
    var toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;

    // Style the toast element
    toast.style.position = 'fixed';
    toast.style.bottom = '20px';
    toast.style.right = '20px';
    toast.style.backgroundColor = '#ea2d57';
    toast.style.color = '#fff';
    toast.style.padding = '15px';
    toast.style.borderRadius = '5px';
    toast.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    toast.style.zIndex = '10000';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.5s';

    // Add the toast to the body
    document.body.appendChild(toast);

    // Show the toast
    setTimeout(function() {
        toast.style.opacity = '1';
    }, 10); // Small delay to ensure the transition works

    // Hide the toast after 9 seconds
    setTimeout(function() {
        toast.style.opacity = '0';
        setTimeout(function() {
            document.body.removeChild(toast);
        }, 500); // Match the transition duration
    }, 9000); // 9 seconds
}
</script>



<!-- Add the JavaScript for displaying the coupon details --> 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all detail toggles
        const detailToggles = document.querySelectorAll('.details-toggle');

        // Add event listeners to each toggle
        detailToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                // Find the closest parent coupon card to ensure we toggle the right details
                const couponCard = this.closest('.coupon-card');
                const details = couponCard.querySelector('.coupon-details');

                // Toggle the visibility of the coupon details
                if (details.style.display === 'none' || details.style.display === '') {
                    details.style.display = 'block';
                } else {
                    details.style.display = 'none';
                }
            });
        });
    });
</script>

<script>
// Get the button and target div elements
const button = document.querySelector('.show-btn');
const targetDiv = document.querySelector('.discount');

// Function to change the background color
function changeBackground() {
  targetDiv.style.backgroundColor = '#b63c47 !important';
}

// Function to reset the background color
function resetBackground() {
  targetDiv.style.backgroundColor = '#FCF8F5 !important ';
}

// Add event listeners for mouseover, mouseout, touchstart, and touchend
button.addEventListener('mouseover', changeBackground);
button.addEventListener('mouseout', resetBackground);
button.addEventListener('touchstart', changeBackground);
button.addEventListener('touchend', resetBackground);

</script>

<script>
// Function to filter coupons
function filterCoupons(filter) {
    const coupons = document.querySelectorAll('.coupons-cards');
    let visibleCount = 0;
    const totalCoupons = coupons.length;
    let dealsCount = 0;
    let codesCount = 0;

    coupons.forEach(coupon => {
        const couponType = coupon.getAttribute('data-type');
        if (filter === 'all' || couponType === filter) {
            coupon.style.display = '';
            visibleCount++;
        } else {
            coupon.style.display = 'none';
        }
        
        // Count total deals and codes
        if (couponType === 'deal') dealsCount++;
        if (couponType === 'code') codesCount++;
    });

    // Update counters
    document.getElementById('total-count').textContent = totalCoupons;
    document.getElementById('deals-count').textContent = dealsCount;
    document.getElementById('codes-count').textContent = codesCount;

    // Update active class
    document.querySelectorAll('.filteration ul a, .mob-filteration ul a').forEach(link => {
        link.querySelector('li').classList.remove('active-li');
        if (link.getAttribute('data-filter') === filter) {
            link.querySelector('li').classList.add('active-li');
        }
    });
}

// Add event listeners to filter links
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.filteration ul a, .mob-filteration ul a');
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            filterCoupons(filter);
        });
    });

    // Initial filter (show all)
    filterCoupons('all');
});
</script>
<script src = 'static_pages/js/script.js'></script>
</body>
</html>";
} elseif ($theme === 'theme2') {


     // Get theme colors
    $theme_color = $page['theme_color'] ?? 'blue';
    $custom_color = $page['custom_color'];
    
$color_posted =hexToRgba($custom_color, alpha: 0.001);

    // Define color mappings
    $colors = [
        'blue' => ['primary' => '#2563eb', 'light' => '#eff6ff', 'lighter' => '#dbeafe'],
        'purple' => ['primary' => '#7c3aed', 'light' => '#f3e8ff', 'lighter' => '#ddd6fe'],
        'green' => ['primary' => '#059669', 'light' => '#ecfdf5', 'lighter' => '#d1fae5'],
        'red' => ['primary' => '#dc2626', 'light' => '#fef2f2', 'lighter' => '#fee2e2']
    ];

 // Get colors based on theme selection
$selected_colors = $theme_color === 'custom' ? [
    'primary' => $custom_color,
    'light' => adjustColorBrightness($custom_color, 150),
    'lighter' => adjustColorBrightness($custom_color, 170)
] : $colors[$theme_color];

echo "
<style>
     /* Tailwind color overrides */
    .bg-white { 
        background-color: " . ($theme_color === 'custom' ? 
            'rgba(' . hexToRgba($custom_color, 0.03) . ')' : 
            '#FFFFFF') . " !important; 
    }
    .bg-gray-50 { 
        background-color: " . ($theme_color === 'custom' ? 
            'rgba(' . hexToRgba($custom_color, 0.05) . ')' : 
            '#F9FAFB') . " !important; 
    }
    
    /* Apply colors based on theme */
    .text-blue-600, 
    .hover\\:text-blue-600:hover,
    .text-blue-700,
    .hover\\:text-blue-700:hover { 
        color: {$selected_colors['primary']} !important; 
    }
    
    .bg-blue-600,
    .hover\\:bg-blue-700:hover { 
        background-color: {$selected_colors['primary']} !important; 
    }
    
    .bg-blue-50,
    .hover\\:bg-blue-100:hover { 
        background-color: " . ($custom_color === 'custom' ? 'rgba(' . hexToRgba($custom_color, 0.01) . ')' : $selected_colors['light']) . " !important; 
        }
    

    .text-blue-600 { 
        color: {$selected_colors['primary']} !important; 
    }
    
    .border-blue-200,
    .hover\\:border-blue-200:hover { 
        border-color: " . ($custom_color === 'custom' ? 'rgba(' . hexToRgba($custom_color, 0.2) . ')' : $selected_colors['lighter']) . " !important; 
    }
    
    .text-gray-300 { 
        color: " . ($custom_color === 'custom' ? 'rgba(' . hexToRgba($custom_color, 0.3) . ')' : $selected_colors['lighter']) . " !important; 
    }
    
    [stroke='#2563eb'] { 
        stroke: {$selected_colors['primary']} !important; 
    }
    
    [fill='#E6F4FF'] { 
        fill: " . ($custom_color === 'custom' ? 'rgba(' . hexToRgba($custom_color, 0.1) . ')' : $selected_colors['light']) . " !important; 
    }
    
    [fill='#0958D9'] { 
        fill: {$selected_colors['primary']} !important; 
    }
    
    /* Button layer colors */
    .button .layer {
        background: {$selected_colors['primary']} !important;
    }
    
    /* Custom color specific styles */
    " . ($theme_color === 'custom' ? "
    .stats-item:hover {
        background-color: rgba(" . hexToRgba($custom_color, 0.05) . ") !important;
    }
    
    /* Card styles for custom theme */
    .card, 
    .bg-white,
    [class*='bg-white'] {
        background-color: rgba(" . hexToRgba($custom_color, 0.03) . ") !important;
        backdrop-filter: blur(8px);
    }
    
    .card:hover {
        background-color: rgba(" . hexToRgba($custom_color, 0.05) . ") !important;
    }
    
    .coupon-button {
        border-color: {$custom_color} !important;
        color: {$custom_color} !important;
        box-shadow: 6px 6px 0 {$custom_color} !important;
    }
    
    .coupon-button:hover {
        box-shadow: 8px 8px 0 {$custom_color} !important;
    }
    
    .coupon-button:active {
        box-shadow: 4px 4px 0 {$custom_color} !important;
    }
    " : "") . "

</style>
  <!DOCTYPE html>
  <html lang='" . ($text_direction == 'rtl' ? 'ar' : 'en') . "' dir='$text_direction'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <script src='https://cdn.tailwindcss.com'></script>
      <title>" . htmlspecialchars($header) . "</title>
      <style>
          @font-face {
              font-family: 'Amazon';
              src: url('static_pages/fonts/AmazonEmberV2-Bold.woff2') format('truetype');
              font-weight: 700;
              font-style: 700;
          }

/* Text formatting classes */
.text-large { font-size: 1.5em !important; }
.text-normal { font-size: 1em !important; }
.text-small { font-size: 0.875em !important; }
.spacing-1 { line-height: 1.5 !important; }
.spacing-2 { line-height: 2 !important; }

/* Blog content styles */
.prose-sm div > * { margin-bottom: 1rem; }
.prose-sm div > *:last-child { margin-bottom: 0; }
.prose-sm div p { margin-bottom: 1rem; }
.prose-sm div strong { font-weight: 600; }
.prose-sm div em { font-style: italic; }

          body {
              font-family: 'Amazon', sans-serif;
          }
          .coupon-card {
              transition: all 0.3s ease;
          }
          .coupon-card:hover {
              transform: translateY(-2px);
          }
          .hover-scale {
              transition: transform 0.2s ease;
          }
          .hover-scale:hover {
              transform: scale(1.05);
          }
          .stats-item {
              transition: all 0.2s ease;
          }
          .stats-item:hover {
              background-color: #f8fafc;
          }
          .coupon-button {
              position: relative;
              padding: 12px 32px;
              background: white;
              border: 2px solid #2563eb;
              border-radius: 10px;
              color: #2563eb;
              font-weight: 500;
              font-size: 14px;
              transition: all 0.3s ease;
              transform-style: preserve-3d;
              box-shadow: 6px 6px 0 #2563eb;
          }
          .coupon-button:hover {
              transform: translate(-2px, -2px);
              box-shadow: 8px 8px 0 #2563eb;
          }
          .coupon-button:active {
              transform: translate(2px, 2px);
              box-shadow: 4px 4px 0 #2563eb;
          }
          .coupon-card {
              border: 1px solid #e5e7eb;
              padding: 24px;
              border-radius: 16px;
          }
          .discount-badge {
              background: #eff6ff;
              padding: 12px 24px;
              border-radius: 12px;
              color: #2563eb;
              font-size: 24px;
              font-weight: bold;
          }
          @media (max-width: 768px) {
              .stats-mobile-hidden {
                  display: none;
              }
          }
          .blog-card {
              transition: all 0.3s ease;
              border: 1px solid #e5e7eb;
              border-radius: 16px;
          }
          .blog-card:hover {
              transform: translateY(-2px);
              box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
          }
          .button {
              text-align: left;
              font-size: 14px;
              line-height: 22px;
              font-weight: 400;
              border: 0;
              position: relative;
              border-radius: 6px;
              min-width: 222px;
              padding: 8px;
              text-transform: uppercase;
              color: #444444;
              background: #EAEAEA;
              cursor: pointer;
              margin-top: 0;
          }
          .code-text {
              text-align: left;
              display: block;
              padding-right: 8px;
          }
          .button .layer {
              position: absolute;
              right: 0;
              top: 0;
              color: #ffffff;
              background: linear-gradient(90deg, #2563eb, #1d4ed8);
              font-size: 14px;
              border-radius: 4px;
              transition: all 0.5s ease;
              min-width: 85%;
              text-align: left;
              height: 100%;
              display: flex;
              align-items: center;
              justify-content: center;
          }
          .button:hover .layer {
              min-width: 75%;
          }
          .button {
              min-width: 180px;
              font-size: 12px;
          }
          /* LTR Styles */
          html[dir='ltr'] {
              direction: ltr;
          }
          html[dir='ltr'] .text-right {
              text-align: left;
          }
          html[dir='ltr'] .button {
              text-align: right;
          }
          html[dir='ltr'] .code-text {
              text-align: right;
              padding-left: 0;
              padding-right: 0;
          }
          html[dir='ltr'] .button .layer {
              left: 0;
              right: auto;
              text-align: right;
          }
          html[dir='ltr'] .flex-row-reverse {
              flex-direction: row;
          }
          html[dir='ltr'] .mr-auto {
              margin-left: auto;
              margin-right: 0;
          }
          html[dir='ltr'] .ml-auto {
              margin-right: auto;
              margin-left: 0;
          }
          html[dir='ltr'] .modal-close {
              right: 2px;
              left: auto;
          }
          html[dir='ltr'] .button:hover .layer {
              width: 75%;
          }
          @media (max-width: 768px) {
              html[dir='ltr'] .button {
                  min-width: 160px;
              }
          }
      </style>
  </head>
  
   <body class='bg-gray-50'>
        <header class='bg-white shadow-sm sticky top-0 z-50'>
            <div class='max-w-6xl mx-auto px-10'>
                <div class='flex justify-between items-center h-16'>
                    <!-- Logo/Site Name -->
                    <a href='#' class='text-2xl font-bold text-blue-600 hover:text-blue-700'>
                        <img src='" . htmlspecialchars($site_logo_path) . "' alt='" . htmlspecialchars($store_name) . "' class='h-8 w-auto'>
                    </a>
                    
                    <!-- Desktop Menu -->
                    <div class='hidden md:flex items-center gap-6'>
                        <div class='text-sm text-gray-600'>
                        <a href='$default_coupon_url'>
                            " . htmlspecialchars($translations['best coupons']) . "
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class='md:hidden rounded-lg p-2 hover:bg-gray-100' aria-label='Menu'>
                        <svg class='w-6 h-6 text-gray-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6h16M4 12h16M4 18h16'></path>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

   <main class='max-w-6xl mx-auto px-4 sm:px-10 py-6'>
        <!-- Store Info Card -->
        <div class='w-full bg-white rounded-xl shadow-sm border mb-2'>
            <div class='px-2 sm:px-6 pt-6 pb-2'> 
                <div class='flex flex-col sm:flex-row gap-0 sm:gap-8 items-start'>

                    <!-- Mobile Layout -->
               <div class='flex flex-row sm:hidden gap-2 w-full mb-4'>
    <div class='w-[100px] h-[75px] flex-shrink-0'>
        <img src='static_pages/$logo' 
            alt='" . htmlspecialchars($store_name) . "' 
            class='w-full h-full object-contain hover-scale'/>
    </div>
    <div class='flex-1 flex flex-col justify-center'>
        <h1 class='text-base md:text-2xl font-bold'>" . htmlspecialchars($header) . "</h1>
        <p class='text-xs md:text-sm hidden'>" . htmlspecialchars($total_coupons) . "  " . htmlspecialchars($translations['available deals and coupons']) . "</p>
    </div>
</div>

                    <!-- Desktop Layout -->
                    <a href='$default_coupon_url'>
                    <div class='hidden sm:block w-[210px] flex-shrink-0'>
                        <img src='static_pages/$logo' 
                            alt='" . htmlspecialchars($store_name) . "' 
                            class='w-full object-contain hover-scale'/>
                    </div>
</a>
                    <div class='flex-1 text-right w-full'>
                        <div class='hidden sm:block'>
                            <h1 class='text-base md:text-2xl font-bold mb-3'>" . htmlspecialchars($header) . "</h1>
                            <p class='text-xs md:text-sm mb-4'  style='font-family: sans-serif;font-weight: 700;'>" . htmlspecialchars($total_coupons) . "  " . htmlspecialchars($translations['available deals and coupons']) . "</p>
                        </div>
<div class='flex gap-5 max-w-md'>
    <button class='flex-1 py-3 bg-blue-600 text-white rounded-lg text-xs md:text-sm font-medium hover:bg-blue-700 transition-colors' data-filter='all'>
        " . htmlspecialchars($translations['all']) . " <span class='count'>(" . htmlspecialchars($total_coupons) . ")</span>
    </button>
    <button class='flex-1 py-3 bg-blue-50 text-blue-600 rounded-lg text-xs md:text-sm font-medium hover:bg-blue-100 transition-colors' data-filter='code'>
        " . htmlspecialchars($translations['codes']) . " <span class='count'>(" . htmlspecialchars($total_codes) . ")</span>
    </button>
    <button class='flex-1 py-3 bg-blue-50 text-blue-600 rounded-lg text-xs md:text-sm font-medium hover:bg-blue-100 transition-colors' data-filter='deal'>
        " . htmlspecialchars($translations['deals']) . " <span class='count'>(" . htmlspecialchars($total_deals) . ")</span>
    </button>
</div>
                    </div>
                </div>
            </div>
        </div>

        <div class='flex flex-col lg:flex-row-reverse gap-2 lg:gap-6'>
            <div class='flex-1'>
                <div class='space-y-6 mt-4'>";
                
                // Reset coupons result pointer
                mysqli_data_seek($coupons_result, 0);
                
                // Loop through coupons
                while ($coupon = $coupons_result->fetch_assoc()) {
                    $coupon_title = htmlspecialchars($coupon['title']);
                    $coupon_description = htmlspecialchars($coupon['description']);
                    $coupon_code = htmlspecialchars($coupon['code']);
                    $coupon_url = htmlspecialchars($coupon['url']);
                    $coupon_type = htmlspecialchars($coupon['type']);
                    $display_uses = htmlspecialchars($coupon['display_uses']);
                    $coupon_details = htmlspecialchars($coupon['details']);
                    
                    echo "
 <div class='coupon-card bg-white rounded-xl shadow-sm border border-gray-200 hover:border-blue-200 transition-all p-2 md:p-4 cursor-pointer' 
     data-type='$coupon_type' 
     data-coupon-id='$coupon[id]' 
     data-url='$coupon_url'
     data-code='$coupon_code'
     data-expiry='$coupon_expire_date'
    >
     
                        <div class='flex flex-row gap-2 md:gap-4 mb-2 md:mb-4'
                        onclick='openCouponModal(\"$coupon_type\", \"$coupon[id]\", \"$coupon_url\", \"$coupon_description\", \"$coupon_code\", \"$coupon_expire_date\", \"$logo\", \"$store_name\")'>
                            <!-- Discount Badge -->
                            <div class='text-blue-600 text-base md:text-2xl font-bold bg-blue-50 px-2 py-4 md:py-6 rounded-xl w-[70px] md:w-[110px] shrink-0 flex items-center justify-center self-stretch sm:self-auto'>
                                <div class='text-center leading-[1.5] tracking-tight sm:leading-[1.2]'>$coupon_title</div>
                            </div>
                            
                            <!-- Title and button -->
                            <div class='flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between'>
                                <div class='space-y-2 sm:space-y-0'>
                                    <h3 class='font-bold text-sm md:text-xl px-2 md:px-1'>$coupon_description</h3>
                                    
                               <!-- Mobile button -->
<div class='button-container sm:hidden flex justify-end'>
    <button class='button'>
        <span class='layer'>" . ($coupon_type === 'deal' ? htmlspecialchars($translations['get deal']) : htmlspecialchars($translations['show coupon'])) . "</span>
        <span class='code-text'>" . ($coupon_type === 'deal' ? htmlspecialchars($translations['deal']) : substr($coupon_code, 0, 2) . "**") . "</span>
    </button>
</div>

                                    
                                </div>

                          <!-- Desktop button -->
<div class='button-container hidden sm:block'>
    <button class='button'>
        <span class='layer'>" . ($coupon_type === 'deal' ? htmlspecialchars($translations['get deal']) : htmlspecialchars($translations['show coupon'])) . "</span>
        <span class='code-text'>" . ($coupon_type === 'deal' ? htmlspecialchars($translations['deal']) : substr($coupon_code, 0, 2) . "**") . "</span>
    </button>
</div>


                            </div>
                        </div>

                        <hr class='border-gray-200 mb-2 sm:mb-4'/>

                        <!-- Footer section -->
                        <div class='flex items-center justify-between text-sm text-gray-600 w-full'>
<button class='details-button flex items-center gap-2 hover:text-blue-600' onclick='toggleDetails(this)'>
   <svg class='w-4 md:w-5 h-4 md:h-5' fill='none' stroke='#2563eb' viewBox='0 0 24 24'>
       <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'></path>
   </svg>
   <span class='text-xs font-normal md:text-sm md:font-medium'>" . htmlspecialchars($translations['details']) . "</span>
</button>

                            <div class='flex items-center gap-4 md:gap-6'>
 <span class='flex items-center gap-1'>
    <svg width='24' height='24' viewBox='0 0 24 26' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <circle cx='12' cy='12' r='12' fill='#E6F4FF'></circle>
        <path d='M14.1745 19.3404H10.1745C6.55448 19.3404 5.00781 17.7937 5.00781 14.1737V10.1737C5.00781 6.55375 6.55448 5.00708 10.1745 5.00708H14.1745C17.7945 5.00708 19.3411 6.55375 19.3411 10.1737V14.1737C19.3411 17.7937 17.7945 19.3404 14.1745 19.3404ZM10.1745 6.00708C7.10115 6.00708 6.00781 7.10041 6.00781 10.1737V14.1737C6.00781 17.2471 7.10115 18.3404 10.1745 18.3404H14.1745C17.2478 18.3404 18.3411 17.2471 18.3411 14.1737V10.1737C18.3411 7.10041 17.2478 6.00708 14.1745 6.00708H10.1745Z' fill='#0958D9'></path>
        <path d='M14.5078 11.1738C13.6811 11.1738 13.0078 10.5005 13.0078 9.67383C13.0078 8.84716 13.6811 8.17383 14.5078 8.17383C15.3345 8.17383 16.0078 8.84716 16.0078 9.67383C16.0078 10.5005 15.3345 11.1738 14.5078 11.1738ZM14.5078 9.17383C14.2345 9.17383 14.0078 9.40049 14.0078 9.67383C14.0078 9.94716 14.2345 10.1738 14.5078 10.1738C14.7811 10.1738 15.0078 9.94716 15.0078 9.67383C15.0078 9.40049 14.7811 9.17383 14.5078 9.17383Z' fill='#0958D9'></path>
        <path d='M9.83984 11.1738C9.01318 11.1738 8.33984 10.5005 8.33984 9.67383C8.33984 8.84716 9.01318 8.17383 9.83984 8.17383C10.6665 8.17383 11.3398 8.84716 11.3398 9.67383C11.3398 10.5005 10.6665 11.1738 9.83984 11.1738ZM9.83984 9.17383C9.56651 9.17383 9.33984 9.40049 9.33984 9.67383C9.33984 9.94716 9.56651 10.1738 9.83984 10.1738C10.1132 10.1738 10.3398 9.94716 10.3398 9.67383C10.3398 9.40049 10.1132 9.17383 9.83984 9.17383Z' fill='#0958D9'></path>
        <path d='M12.1738 17.1405C10.2405 17.1405 8.6733 15.5672 8.67383 13.6405C8.67383 13.0339 9.16716 12.5405 9.77383 12.5405H14.5738C15.1805 12.5405 15.6738 13.0339 15.6738 13.6405C15.6738 15.5672 14.1072 17.1405 12.1738 17.1405ZM9.77383 13.5405C9.72049 13.5405 9.67383 13.5872 9.67383 13.6405C9.67383 15.0205 10.7938 16.1405 12.1738 16.1405C13.5538 16.1405 14.6738 15.0205 14.6738 13.6405C14.6738 13.5872 14.6272 13.5405 14.5738 13.5405H9.77383Z' fill='#0958D9'></path>
    </svg>
    <span class='text-xs font-normal md:text-sm md:font-medium'>$display_uses " . htmlspecialchars($translations['users today']) . "</span>
</span>

<button class='share-button flex items-center gap-2 hover:text-blue-600' 
        onclick='handleShareModal(" . json_encode($coupon['id']) . ", " . json_encode($coupon_description) . ")'>
    <svg class='w-4 md:w-5 h-4 md:h-5' fill='none' stroke='#2563eb' viewBox='0 0 24 24'>
        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z'/>
    </svg>
    <p class='hidden md:block'>" . htmlspecialchars($translations['share']) . "</p>
</button>
                            </div>
                        </div>

                        <!-- Details Content -->
                        <div class='details-content mt-4 bg-gray-50 rounded-lg p-4 hidden'>
                            <div class='text-gray-600 text-sm space-y-2'>
                                <p>$coupon_details</p>
                            </div>
                        </div>
                    </div>
                    

                    ";
                }

                echo "
                </div>
            </div>

            <!-- Sidebar -->
            <aside class='w-full lg:w-60 mt-0 lg:mt-4'>
                <!-- Stats Card -->
                <div class='bg-white rounded-xl shadow-sm border p-4 space-y-2 stats-mobile-hidden'>
                    <div class='stats-item flex items-center justify-between p-3 rounded-lg'>
                        <div class='flex items-center gap-2 text-gray-600'>
                           <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'/>
                    </svg>
                            <span>" . htmlspecialchars($translations['users today']) . "</span>
                        </div>
    <span class='text-lg font-medium'>" . number_format($total_uses) . "</span>
                    </div>

                    <div class='stats-item flex items-center justify-between p-3 rounded-lg'>
                        <div class='flex items-center gap-2 text-gray-600'>
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'/>
                            </svg>
                            <span>" . htmlspecialchars($translations['codes']) . "</span>
                        </div>
                        <span class='text-lg font-medium'>$total_codes</span>
                    </div>

                    <div class='stats-item flex items-center justify-between p-3 rounded-lg'>
                        <div class='flex items-center gap-2 text-gray-600'>
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7'></path>
                            </svg>
                            <span>" . htmlspecialchars($translations['deals']) . "</span>
                        </div>
                        <span class='text-lg font-medium'>$total_deals</span>
                    </div>
                </div>

                <!-- Rating Card -->
                <div class='bg-white rounded-xl shadow-sm border p-4 space-y-2 mt-4 hidden md:block'>
                    <div class='text-center mb-4'>
                        <h3 class='text-xl font-bold mb-4'>" . htmlspecialchars($translations['rating']) . "</h3>
                        <div class='flex justify-center gap-1 mb-2'>";
                        
                        // Generate star rating
                        for ($i = 1; $i <= 5; $i++) {
                            $starClass = $i <= $rating ? 'text-blue-600' : 'text-gray-300';
                            echo "<svg class='w-6 h-6 $starClass' fill='currentColor' viewBox='0 0 24 24'>
                                    <path d='M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z'/>
                                </svg>";
                        }
                        
                        echo 
                        "
                        </div>
                        <p class='text-sm text-gray-600'>" . htmlspecialchars($translations['vote']) . " " . htmlspecialchars($votes) . " " . htmlspecialchars($translations['customers_voted']) . " " . $rating . " " . htmlspecialchars($translations['stars']) . "</p>
                    </div>
                </div>
            </aside>
        </div>
<!-- Blog Section -->
        <div class='bg-white rounded-xl shadow-sm border p-6 mt-8'>
            <h2 class='text-xl font-bold mb-4'> " . htmlspecialchars($translations['details']) . " " . htmlspecialchars($store_name) . "</h2>
            
 <article class='prose prose-sm max-w-none'>
    <div class='text-gray-600 mb-4 leading-relaxed' style='font-size: 14px; font-family: sans-serif;'>
         $blog
    </div>
</article>

        </div>
    </main>



    <!-- Coupon Modal -->
    <div id='couponModal' class='fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-start sm:items-center justify-center overflow-y-auto p-4 sm:p-6'>
        <div class='bg-white rounded-xl w-full max-w-xl mx-auto relative transform transition-all mt-16 sm:mt-0'>
<!-- Close button --><!-- Close button -->
<button onclick='closeModal()' class='absolute " . ($text_direction == 'rtl' ? 'left-2 sm:left-4' : 'right-2 sm:right-4') . " top-2 sm:top-4 text-gray-400 hover:text-gray-600 transition-colors p-2'>
    <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'>
        <path d='M6 18L18 6M6 6l12 12' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
    </svg>
</button>

    
            <!-- Header -->
            <div class='bg-gray-50 p-4 sm:p-6 rounded-t-xl border-b'>
                <div class='flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4'>
                    <img id='modalLogo' src='' alt='' class='h-20 sm:h-15 order-1 sm:order-none'/>
                    <h2 class='text-lg sm:text-xl font-bold text-center " . ($text_direction == 'rtl' ? 'sm:text-right' : 'sm:text-left') . " flex-1' id='modalTitle'></h2>
                </div>
            </div>
    
        <!-- Content -->
<div class='p-4 sm:p-6 space-y-4 sm:space-y-6'>
   <!-- Dynamic message container - only one will show -->
   <div id='messageContainer'>
       <div id='dealMsg' class='text-center hidden'>         
           <p class='text-sm text-gray-600'>" . htmlspecialchars($translations['deal msg start']) . "              
               <a href='' id='modalStoreUrl2' target='_blank'>                 
                   <span id='modalStoreName2' class='text-blue-600 hover:text-blue-700 font-medium'></span>             
               </a>         
           </p>     
       </div>

       <div id='codeMsg' class='text-center hidden'>     
           <p class='text-sm text-gray-600'>" . htmlspecialchars($translations['copy msg']) . "          
               <a href='' id='modalStoreUrl' target='_blank'>             
                   <span id='modalStoreName' class='text-blue-600 hover:text-blue-700 font-medium'></span>         
               </a>     
           </p> 
       </div>
   </div>

<div id='dealSection' class='hidden'>
    <button onclick='handleDeal()' class='w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg flex items-center justify-center gap-2 transition-colors'>
        " . htmlspecialchars($translations['get deal']) . "
    </button>
    <div id='dealSuccess' class='hidden mt-4'>
        <div class='text-center'>
            <p class='bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm text-center shadow-lg sm:shadow-none'>" . htmlspecialchars($translations['deal msg']) . " 
                <a href='' id='modalStoreUrl2'>
                    <span id='modalStoreName2' class='text-blue-600 hover:text-blue-700 font-medium'></span>
                </a>
            </p>
        </div>
    </div>
</div>
    
                <!-- Coupon display and copy section -->
                <div id='codeSection' class='relative'>
                    <!-- Mobile layout -->
                    <div class='block sm:hidden space-y-3'>
                        <div class='bg-gray-50 p-4 rounded-lg text-center border-2 border-dashed border-gray-200'>
                            <span id='couponCodeMobile' class='font-mono text-lg font-bold'></span>
                        </div>
                        <button onclick='copyCoupon()' class='w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg flex items-center justify-center gap-2 transition-colors'>
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3'/>
                            </svg>
                            " . htmlspecialchars($translations['copycode']) . "
                        </button>
                    </div>
    
                    <!-- Desktop layout -->
                    <div class='hidden sm:flex items-stretch rounded-lg overflow-hidden border-2 border-dashed border-gray-200'>
                        <div class='flex-1 bg-gray-50 p-4 text-center font-mono text-lg font-bold'>
                            <span id='couponCodeDesktop'></span>
                        </div>
                        <button onclick='copyCoupon()' class='bg-blue-600 hover:bg-blue-700 text-white px-6 flex items-center justify-center gap-2 transition-colors'>
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3'/>
                            </svg>
                            " . htmlspecialchars($translations['copycode']) . "
                        </button>
                    </div>
                </div>
    
                <!-- Verification and expiry -->
                <div class='flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 text-sm'>
                    <div class='flex items-center gap-2 text-green-600'>
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/>
                        </svg>
                        <span class='font-medium'>" . htmlspecialchars($translations['verified']) . "</span>
                    </div>
                    <div class='flex items-center gap-2 text-gray-500'>
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'/>
                        </svg>
                        <span id='modalExpiry' class='text-xs sm:text-sm'></span>
                    </div>
                </div>
    
                <!-- Success message -->
                <div id='copySuccess' class='hidden fixed bottom-4 left-4 right-4 sm:relative sm:bottom-auto sm:left-auto sm:right-auto'>
                    <div class='bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm text-center shadow-lg sm:shadow-none'>
                        " . htmlspecialchars($translations['link copied']) . "
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Share Modal -->
<div id='shareModal' class='fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4'>
    <div class='bg-white rounded-xl w-full max-w-md mx-auto relative'>
        <!-- Close button -->
    <button onclick='handleShareModalClose()' class='absolute left-2 top-2 text-gray-400 hover:text-gray-600 p-2'>
            <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'>
                <path d='M6 18L18 6M6 6l12 12' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
            </svg>
        </button>

        <div class='p-4 border-b text-center'>
            <h3 class='text-lg font-bold'>" . htmlspecialchars($translations['share coupon']) . "</h3>
        </div>

        <div class='p-4 space-y-4'>
            <!-- URL display -->
            <div class='relative'>
                <div class='bg-gray-50 p-3 rounded-lg text-left border-2 border-dashed border-gray-200 mb-3'>
                    <span id='shareUrl' class='text-sm text-gray-600 break-all'></span>
                </div>
    <button onclick='handleShareCopy()' class='w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg flex items-center justify-center gap-2'>
                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3'/>
                    </svg>
                    " . htmlspecialchars($translations['copy link']) . "
                </button>
            </div>

            <!-- Social share buttons -->
            <div class='flex justify-center gap-4'>
    <button onclick='handleShareWhatsApp()' class='p-2 rounded-full bg-green-500 hover:bg-green-600 text-white'>
                    <svg class='w-6 h-6' fill='currentColor' viewBox='0 0 24 24'>
                        <path d='M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM15.85 15.85L14.5 16.5L9.5 11.5V7.5H11V10.8L15.3 15.1L15.85 15.85Z'/>
                    </svg>
                </button>
    <button onclick='handleShareTelegram()' class='p-2 rounded-full bg-blue-500 hover:bg-blue-600 text-white'>
                    <svg class='w-6 h-6' fill='currentColor' viewBox='0 0 24 24'>
                        <path d='M12 2L2 7L12 12L22 7L12 2Z'/>
                        <path d='M2 17L12 22L22 17'/>
                        <path d='M2 12L12 17L22 12'/>
                    </svg>
                </button>
    <button onclick='handleShareTwitter()' class='p-2 rounded-full bg-blue-400 hover:bg-blue-500 text-white'>
                    <svg class='w-6 h-6' fill='currentColor' viewBox='0 0 24 24'>
                        <path d='M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'/>
                    </svg>
                </button>
            </div>

            <!-- Success message -->
            <div id='shareCopySuccess' class='hidden'>
                <div class='bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm text-center'>
                    " . htmlspecialchars($translations['link copied']) . "
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let shareData = {};

function handleShareModal(id, description) {
    shareData = {
        id: id,
        description: description,
        url: window.location.href.split('#')[0] + '#' + id
    };
    
    const modal = document.getElementById('shareModal');
    const urlElement = document.getElementById('shareUrl');
    
    if (modal && urlElement) {
        urlElement.textContent = shareData.url;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function handleShareModalClose() {
    const modal = document.getElementById('shareModal');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function handleShareCopy() {
    const shareUrl = shareData.url;
    navigator.clipboard.writeText(shareUrl).then(() => {
        const successMsg = document.getElementById('shareCopySuccess');
        if (successMsg) {
            successMsg.classList.remove('hidden');
            setTimeout(() => {
                successMsg.classList.add('hidden');
            }, 2000);
        }
    });
}

function handleShareWhatsApp() {
    const shareText = encodeURIComponent(shareData.description  + shareData.url);
    window.open('https://wa.me/?text=' + shareText, '_blank');
}

function handleShareTelegram() {
    const shareUrl = encodeURIComponent(shareData.url);
    const shareText = encodeURIComponent(shareData.description);
    window.open('https://t.me/share/url?url=' + shareUrl + '&text=' + shareText, '_blank');
}

function handleShareTwitter() {
    const shareUrl = encodeURIComponent(shareData.url);
    const shareText = encodeURIComponent(shareData.description);
    window.open('https://twitter.com/intent/tweet?url=' + shareUrl + '&text=' + shareText, '_blank');
}

// Close share modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('shareModal');
    if (event.target === modal) {
        handleShareModalClose();
    }
});

// Close share modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        handleShareModalClose();
    }
});
</script>

    


<!-- Script For Filtering Ajax -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter buttons
    const filterButtons = document.querySelectorAll('[data-filter]');
    
    // Add click event to each filter button
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
// Update button styles             
filterButtons.forEach(btn => {                
    btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-100');                
    btn.classList.add('bg-blue-50', 'text-blue-600', 'hover:bg-blue-100');             
});             

// Set clicked button active
this.classList.remove('bg-blue-50', 'text-blue-600', 'hover:bg-blue-100');             
this.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');


// Get all coupon cards
            const couponCards = document.querySelectorAll('[data-type]');
            
            // Filter coupons
            couponCards.forEach(card => {
                const type = card.getAttribute('data-type');
                if (filter === 'all' || type === filter) {
                    card.style.display = '';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.transition = 'opacity 0.3s ease';
                        card.style.opacity = '1';
                    }, 10);
                } else {
                    card.style.transition = 'opacity 0.3s ease';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });

            // Update counts
            updateCounts();
        });
    });

    function updateCounts() {
        const visibleCards = document.querySelectorAll('[data-type]:not([style*=\"display: none\"])');
        const totalCouponCount = document.querySelector('[data-filter=\"all\"] .count');
        const codesCount = document.querySelector('[data-filter=\"code\"] .count');
        const dealsCount = document.querySelector('[data-filter=\"deal\"] .count');

        let totalVisible = visibleCards.length;
        let visibleCodes = Array.from(visibleCards).filter(card => card.getAttribute('data-type') === 'code').length;
        let visibleDeals = Array.from(visibleCards).filter(card => card.getAttribute('data-type') === 'deal').length;

        if (totalCouponCount) totalCouponCount.textContent = `(\${totalVisible})`;
        if (codesCount) codesCount.textContent = `(\${visibleCodes})`;
        if (dealsCount) dealsCount.textContent = `(\${visibleDeals})`;
    }

    // Initial count update
    updateCounts();
});
</script>



<!-- Script For Details -->

<script>
function toggleDetails(button) {
    const detailsContent = button.closest('.coupon-card').querySelector('.details-content');
    const svg = button.querySelector('svg');
    
    // Toggle visibility
    detailsContent.classList.toggle('hidden');
    
    // Rotate arrow
    if (detailsContent.classList.contains('hidden')) {
        svg.style.transform = 'rotate(0deg)';
    } else {
        svg.style.transform = 'rotate(180deg)';
    }
}
</script>


<script>
// Initialize translations correctly by using PHP variables
const translations = {
    'users_today': '" . htmlspecialchars($translations['users today']) . "',
    'link_copied': '" . htmlspecialchars($translations['link copied']) . "'
};

let currentCouponData = {};

function openCouponModal(type, id, url, description, code, expiry, logo, storeName) {
    // Store all data needed for the modal
    sessionStorage.setItem('modalData', JSON.stringify({
        type, id, url, description, code, expiry, logo, storeName
    }));
    
    // Track usage first
    fetch('track_usage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'coupon_id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const usageElement = document.querySelector('#usage-count-' + id);
            if (usageElement) {
                usageElement.innerHTML = '<i class=\"fas fa-users\" style=\"color: #FF4639; font-size: 0.9em;\"></i> ' + 
                data.count + ' ' + translations.users_today;
            }
        }
    });

    // If it's a code type, copy it automatically
    if (type !== 'deal' && code) {
        navigator.clipboard.writeText(code).then(() => {
            // Show success message in a toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm shadow-lg z-50';
            toast.textContent = translations.link_copied + ': ' + code;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }).catch(err => {
            console.error('Failed to copy code:', err);
        });
    }

    // Open new tab with hash and show modal there
    const newTabUrl = window.location.pathname + '#' + id;
    window.open(newTabUrl, '_blank');

    // Redirect current tab to merchant URL
    window.location.href = url;
}

// Handle modal display in new tab
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.substring(1);
    if (hash) {
        const modalData = JSON.parse(sessionStorage.getItem('modalData') || '{}');
        if (modalData.id === hash) {
            const modal = document.getElementById('couponModal');
            if (modal) {
                // Normal modal setup
                document.getElementById('modalTitle').textContent = modalData.description;
                document.getElementById('modalLogo').src = 'static_pages/' + modalData.logo;
                document.getElementById('modalLogo').alt = modalData.storeName;

                // Get references
                const copyMsgDiv = document.querySelector('.text-center');
                const codeSection = document.getElementById('codeSection');
                const dealSection = document.getElementById('dealSection');

                console.log('Coupon type:', modalData.type); // Debug

   if (modalData.type && modalData.type.trim() === 'deal') {
    document.getElementById('codeMsg').classList.add('hidden');
    document.getElementById('dealMsg').classList.remove('hidden');
    codeSection.style.display = 'none';
    dealSection.style.display = 'block';
    document.getElementById('modalStoreName2').textContent = modalData.storeName;
    document.getElementById('modalStoreUrl2').href = modalData.url;
} else {
    document.getElementById('codeMsg').classList.remove('hidden');
    document.getElementById('dealMsg').classList.add('hidden');
    codeSection.style.display = 'block';
    dealSection.style.display = 'none';
    document.getElementById('couponCodeMobile').textContent = modalData.code;
    document.getElementById('couponCodeDesktop').textContent = modalData.code;
    document.getElementById('modalStoreName').textContent = modalData.storeName;
    document.getElementById('modalStoreUrl').href = modalData.url;
}

// Add expiry time and store names to both modals
document.getElementById('modalExpiry').textContent = modalData.expiry;
document.getElementById('modalStoreName').textContent = modalData.storeName;
document.getElementById('modalStoreUrl').href = modalData.url;
document.getElementById('modalStoreName2').textContent = modalData.storeName;
document.getElementById('modalStoreUrl2').href = modalData.url;
                modal.classList.remove('hidden');
                modal.classList.add('flex', 'modal-animate-in');
                currentCouponData = modalData;
            }
        }
        sessionStorage.removeItem('modalData');
    }
});
function closeModal() {
    const modal = document.getElementById('couponModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex', 'modal-animate-in');
    document.getElementById('copySuccess').classList.add('hidden');
    history.pushState({}, '', window.location.pathname);
}

function copyCoupon() {
    const code = currentCouponData.code;
    navigator.clipboard.writeText(code).then(() => {
        const successMsg = document.getElementById('copySuccess');
        successMsg.classList.remove('hidden');
        successMsg.classList.add('copy-success-animate');

        setTimeout(() => {
            successMsg.classList.add('hidden');
        }, 3000);

        window.open(currentCouponData.url, '_blank');
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('couponModal');
    if (event.target === modal) {
        closeModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Handle browser back button
window.addEventListener('popstate', function() {
    if (!window.location.hash) {
        closeModal();
    }
});


function handleDeal() {
    document.getElementById('dealSuccess').classList.remove('hidden');
    setTimeout(() => {
        window.open(currentCouponData.url, '_blank');
    }, 1500);
}
</script>

</body>
</html>
";
} // Close theme2 condition
}
 else {
    echo "No route provided.";
}
?>

